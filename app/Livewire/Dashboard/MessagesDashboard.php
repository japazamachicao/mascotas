<?php

namespace App\Livewire\Dashboard;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewChatMessage;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MessagesDashboard extends Component
{
    public $activeConversationId = null;
    public $newMessageBody = '';
    public $searchQuery = '';

    protected $queryString = [
        'activeConversationId' => ['except' => null, 'as' => 'c']
    ];

    public function mount($contactId = null)
    {
        $userId = Auth::id();

        // Si se provee un contactId en la URL, intentamos abrir o crear la conversación
        if ($contactId && $contactId != $userId) {
            $contact = User::findOrFail($contactId);

            // Determinar quién es cliente y quién proveedor
            // El usuario autenticado es cliente si el contacto tiene rol de proveedor
            // Para simplificar, si el contacto tiene rol de proveedor, el contacto es el provider_id
            $isContactProvider = $contact->hasAnyRole(['veterinarian', 'walker', 'groomer', 'hotel', 'shelter', 'trainer', 'pet_sitter', 'pet_taxi', 'pet_photographer']);
            
            $clientId = $isContactProvider ? $userId : $contactId;
            $providerId = $isContactProvider ? $contactId : $userId;

            $conversation = Conversation::firstOrCreate([
                'client_id' => $clientId,
                'provider_id' => $providerId,
            ]);

            $this->activeConversationId = $conversation->id;
            $this->markAsRead($conversation->id);
        }
    }

    public function selectConversation($conversationId)
    {
        $conversation = Conversation::where(function ($q) {
            $q->where('client_id', Auth::id())
              ->orWhere('provider_id', Auth::id());
        })->findOrFail($conversationId);

        $this->activeConversationId = $conversation->id;
        $this->markAsRead($conversation->id);
        $this->reset('newMessageBody');
    }

    private function markAsRead($conversationId)
    {
        Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        // Emitir un evento global para actualizar campanas de notificaciones si es necesario
        $this->dispatch('messages-read');
    }

    public function sendMessage()
    {
        $this->validate([
            'newMessageBody' => 'required|string|max:2000'
        ], [
            'newMessageBody.required' => 'El mensaje no puede estar vacío.'
        ]);

        $conversation = Conversation::where(function ($q) {
            $q->where('client_id', Auth::id())
              ->orWhere('provider_id', Auth::id());
        })->findOrFail($this->activeConversationId);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $this->newMessageBody,
            'is_read' => false,
        ]);

        $conversation->touch(); // Actualizar updated_at para subir el hilo al inicio

        // Enviar notificación al receptor
        $recipientId = ($conversation->client_id === Auth::id()) ? $conversation->provider_id : $conversation->client_id;
        $recipient = User::find($recipientId);
        if ($recipient) {
            $recipient->notify(new NewChatMessage($message));
        }

        $this->reset('newMessageBody');
        
        // Despachar evento para scroll del chat
        $this->dispatch('message-sent');
    }

    public function render()
    {
        $userId = Auth::id();

        // Obtener las conversaciones filtradas por búsqueda
        $conversationsQuery = Conversation::where(function ($q) use ($userId) {
            $q->where('client_id', $userId)
              ->orWhere('provider_id', $userId);
        })
        ->with(['client', 'provider', 'messages' => function ($q) {
            $q->latest();
        }]);

        $conversations = $conversationsQuery->get()
            ->map(function ($convo) use ($userId) {
                // Agregar metadata para simplificar la renderización en Blade
                $contact = ($convo->client_id === $userId) ? $convo->provider : $convo->client;
                $convo->contact_user = $contact;
                $convo->last_message = $convo->messages->first();
                $convo->unread_count = $convo->unreadMessagesFor($userId);
                return $convo;
            });

        // Filtrar por búsqueda si se ingresó texto
        if (!empty($this->searchQuery)) {
            $query = strtolower($this->searchQuery);
            $conversations = $conversations->filter(function ($convo) use ($query) {
                return str_contains(strtolower($convo->contact_user->name), $query);
            });
        }

        // Ordenar por el último mensaje (o fecha de creación si no hay mensajes)
        $conversations = $conversations->sortByDesc(function ($convo) {
            return $convo->last_message ? $convo->last_message->created_at->timestamp : $convo->updated_at->timestamp;
        });

        // Cargar los mensajes de la conversación activa si la hay
        $activeMessages = [];
        $activeContact = null;

        if ($this->activeConversationId) {
            $activeConvo = Conversation::where(function ($q) use ($userId) {
                $q->where('client_id', $userId)
                  ->orWhere('provider_id', $userId);
            })
            ->with(['client', 'provider', 'messages.sender'])
            ->find($this->activeConversationId);

            if ($activeConvo) {
                $activeMessages = $activeConvo->messages->sortBy('created_at');
                $activeContact = ($activeConvo->client_id === $userId) ? $activeConvo->provider : $activeConvo->client;
            }
        }

        return view('livewire.dashboard.messages-dashboard', [
            'conversations' => $conversations,
            'activeMessages' => $activeMessages,
            'activeContact' => $activeContact,
        ])->layout('components.layouts.app');
    }
}
