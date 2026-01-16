@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-br from-gray-50 via-gray-50 to-blue-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('messages.index') }}" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-700 font-semibold mb-6">
                ← Retour aux messages
            </a>

            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-3xl shadow-lg">
                    👤
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $otherUser->name }}</h1>
                    <p class="text-gray-600">{{ $otherUser->email }}</p>
                </div>
            </div>
        </div>

        <!-- Messages de succès -->
        @if(session('success'))
            <div class="mb-8 bg-green-50 border-l-4 border-green-600 p-4 rounded-lg">
                <p class="text-green-800 font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Conteneur de messages -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-8">
            <!-- Messages -->
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto bg-gray-50">
                @forelse($messages as $msg)
                    <div class="flex {{ $msg->from_user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-md {{ $msg->from_user_id === Auth::id() ? 'bg-purple-500 text-white' : 'bg-white border border-gray-200 text-gray-900' }} rounded-lg p-4 shadow">
                            <p class="text-sm">{{ $msg->contenu }}</p>
                            <p class="text-xs {{ $msg->from_user_id === Auth::id() ? 'text-purple-100' : 'text-gray-500' }} mt-2">
                                {{ $msg->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">
                        <p class="text-4xl mb-2">💬</p>
                        <p>Aucun message. Commencez une conversation !</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Formulaire d'envoi -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form action="{{ route('messages.reply', $otherUser->id) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Votre Message</label>
                    <textarea
                        name="contenu"
                        placeholder="Tapez votre message..."
                        required
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none @error('contenu') border-red-500 @enderror"
                    ></textarea>
                    @error('contenu')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="flex-1 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3 rounded-lg transition transform hover:scale-105 shadow-lg"
                    >
                        📤 Envoyer
                    </button>
                    <a
                        href="{{ route('messages.index') }}"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition"
                    >
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
