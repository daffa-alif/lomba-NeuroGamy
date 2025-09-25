@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 p-6">
    <div class="w-full max-w-2xl bg-white shadow-lg rounded-2xl p-6">
        <h1 class="text-2xl font-bold mb-4">🤖 Gemini Chatbot</h1>

        <div id="chat-box" class="h-96 overflow-y-auto border rounded-lg p-4 bg-gray-50 mb-4 space-y-3">
            <!-- Messages will appear here -->
        </div>

        <form id="chat-form" class="flex">
            <input type="text" id="message" name="message"
                   class="flex-grow border rounded-l-lg px-4 py-2 focus:outline-none"
                   placeholder="Type your message..." required>
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-r-lg hover:bg-blue-700">
                Send
            </button>
        </form>
    </div>
</div>

<script>
    const chatForm = document.getElementById('chat-form');
    const chatBox = document.getElementById('chat-box');
    const input = document.getElementById('message');

    function appendMessage(sender, text) {
        const msg = document.createElement('div');
        msg.classList.add('p-2', 'rounded-lg', 'max-w-lg');
        if (sender === 'You') {
            msg.classList.add('bg-blue-100', 'self-end');
        } else {
            msg.classList.add('bg-gray-200');
        }
        msg.innerHTML = `<strong>${sender}:</strong> ${text}`;
        chatBox.appendChild(msg);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = input.value;
        appendMessage('You', text);
        input.value = '';

        try {
            const res = await fetch("{{ url('/ai/generate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ prompt: text })
            });
            const data = await res.json();
            // Adjust depending on Gemini response shape
            appendMessage('AI', data.output || JSON.stringify(data));
        } catch (err) {
            appendMessage('AI', '⚠️ Error: ' + err.message);
        }
    });
</script>
@endsection
