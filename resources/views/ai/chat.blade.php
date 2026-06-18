@extends('layouts.app')
@section('title', 'AI Chat Assistant')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-robot me-2"></i>AI Chat Assistant</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">AI</a></li><li class="breadcrumb-item active">Chat Assistant</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2 d-flex align-items-center">
        <i class="fas fa-comment-dots me-2 text-primary"></i>
        <h6 class="fw-semibold mb-0">Conversation</h6>
        <span class="ms-auto badge bg-primary">{{ $messages->total() }} messages</span>
    </div>
    <div class="card-body p-0">
        <div style="height: 400px; overflow-y: auto; padding: 1rem;" id="chatMessages">
            @forelse($messages as $msg)
            <div class="d-flex mb-3 justify-content-end">
                <div class="px-3 py-2 rounded-3 bg-primary text-white" style="max-width: 75%; word-wrap: break-word;">
                    <small class="d-block text-white-50" style="font-size: 0.7rem;">You</small>
                    {{ $msg->message }}
                </div>
                <div class="flex-shrink-0 ms-2">
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-user fa-sm"></i>
                    </div>
                </div>
            </div>
            @if($msg->response)
            <div class="d-flex mb-3 justify-content-start">
                <div class="flex-shrink-0 me-2">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-robot fa-sm"></i>
                    </div>
                </div>
                <div class="px-3 py-2 rounded-3 bg-light text-dark" style="max-width: 75%; word-wrap: break-word;">
                    <small class="d-block text-muted" style="font-size: 0.7rem;">AI Assistant</small>
                    {{ $msg->response }}
                </div>
            </div>
            @endif
            @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-comments fa-3x mb-3"></i>
                <p>No messages yet. Start a conversation with the AI assistant.</p>
            </div>
            @endforelse
        </div>

        <div class="border-top p-3 bg-white">
            <form method="POST" action="{{ route('ai.chat.store') }}" class="d-flex gap-2">
                @csrf
                <textarea name="message" class="form-control" rows="2" placeholder="Type your message..." required></textarea>
                <button type="submit" class="btn btn-primary align-self-end">
                    <i class="fas fa-paper-plane me-1"></i>Send
                </button>
            </form>
        </div>
    </div>
</div>
<x-pagination :paginator="$messages" />
@push('scripts')
<script>
    var chatContainer = document.getElementById('chatMessages');
    if (chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight;
</script>
@endpush
@endsection
