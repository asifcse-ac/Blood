@extends('layouts.user')

@section('title', 'AI Assistant')

@push('styles')
<style>
    .chat-container {
        height: 550px;
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,.08);
        overflow: hidden;
    }

    .chat-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .chat-header .ai-icon {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #f8f9fa;
    }

    .message {
        margin-bottom: 15px;
        display: flex;
        gap: 10px;
    }

    .message.user {
        flex-direction: row-reverse;
    }

    .message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .message.assistant .message-avatar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .message.user .message-avatar {
        background: #dc3545;
        color: white;
    }

    .message-content {
        max-width: 75%;
        padding: 15px 18px;
        border-radius: 18px;
        line-height: 1.6;
    }

    .message.assistant .message-content {
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
    }

    .message.user .message-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .chat-input-area {
        padding: 20px;
        background: white;
        border-top: 1px solid #eee;
    }

    .chat-input-wrapper {
        display: flex;
        gap: 10px;
    }

    .chat-input {
        flex: 1;
        border: 2px solid #eee;
        border-radius: 25px;
        padding: 12px 20px;
        font-size: 15px;
        transition: border-color 0.2s;
    }

    .chat-input:focus {
        outline: none;
        border-color: #667eea;
    }

    .send-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 18px;
        cursor: pointer;
        transition: transform 0.2s, opacity 0.2s;
    }

    .send-btn:hover {
        transform: scale(1.05);
        opacity: 0.9;
    }

    .send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .typing-indicator {
        display: none;
        padding: 12px 18px;
        background: white;
        border-radius: 18px;
        box-shadow: 0 2px 10px rgba(0,0,0,.05);
        width: fit-content;
    }

    .typing-indicator.show {
        display: block;
    }

    .typing-indicator span {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #667eea;
        border-radius: 50%;
        margin: 0 2px;
        animation: typing 1s infinite;
    }

    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 100% { opacity: 0.4; transform: translateY(0); }
        50% { opacity: 1; transform: translateY(-5px); }
    }

    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .quick-action-btn {
        padding: 8px 16px;
        border: 2px solid #667eea;
        background: white;
        color: #667eea;
        border-radius: 20px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .quick-action-btn:hover {
        background: #667eea;
        color: white;
    }

    .welcome-message {
        text-align: center;
        padding: 30px 20px;
        color: #666;
    }

    .welcome-message i {
        font-size: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 15px;
    }

    .message-content h4 {
        margin-bottom: 10px;
        font-size: 16px;
    }

    .request-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        margin-top: 12px;
        border-left: 4px solid #28a745;
    }

    .request-card.pending {
        border-left-color: #ffc107;
    }

    .request-card.urgent {
        border-left-color: #dc3545;
    }

    .stock-alert {
        background: #fff3cd;
        border-radius: 10px;
        padding: 12px;
        margin-top: 10px;
        border-left: 4px solid #ffc107;
    }

    .stock-alert.critical {
        background: #f8d7da;
        border-left-color: #dc3545;
    }

    .action-buttons {
        margin-top: 15px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        font-size: 13px;
        padding: 8px 16px;
    }

    .stock-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-top: 10px;
    }

    .stock-item {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
        text-align: center;
        font-size: 13px;
    }

    .stock-item.available { border-left: 3px solid #28a745; }
    .stock-item.limited { border-left: 3px solid #ffc107; }
    .stock-item.empty { border-left: 3px solid #dc3545; }

    .stock-item .group {
        font-weight: bold;
        font-size: 16px;
    }

    .stock-item .units {
        color: #666;
    }
</style>
@endpush

@section('content')
<div class="hero-section text-center">
    <h1><i class="fas fa-robot"></i> AI Blood Request Assistant</h1>
    <p class="lead">Tell me what you need - I'll check availability and create your request automatically</p>
</div>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="quick-actions">
            <button class="quick-action-btn" onclick="sendQuickMessage('I need 2 units of A+ blood urgently at City Hospital')">
                <i class="fas fa-tint"></i> Request Blood
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('Check blood stock availability')">
                <i class="fas fa-warehouse"></i> Check Stock
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('Find B+ donors near me')">
                <i class="fas fa-users"></i> Find Donors
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('I need 5 units of O- blood for emergency surgery')">
                <i class="fas fa-ambulance"></i> Emergency Request
            </button>
        </div>

        <div class="chat-container">
            <div class="chat-header">
                <div class="ai-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <div>
                    <h5 class="mb-0">BloodLink AI Assistant</h5>
                    <small><i class="fas fa-circle text-success"></i> Ready to help</small>
                </div>
            </div>

            <div class="chat-messages" id="chatMessages">
                <div class="welcome-message">
                    <i class="fas fa-heartbeat"></i>
                    <h4>Hello! I'm your Blood Request Assistant</h4>
                    <p>Just tell me what you need in your own words:</p>
                    <ul class="list-unstyled text-start" style="max-width: 350px; margin: 0 auto;">
                        <li><i class="fas fa-check text-success"></i> "I need 2 units of A+ blood"</li>
                        <li><i class="fas fa-check text-success"></i> "3 units O- urgently at Memorial Hospital"</li>
                        <li><i class="fas fa-check text-success"></i> "Check blood stock"</li>
                        <li><i class="fas fa-check text-success"></i> "Find nearby donors"</li>
                    </ul>
                    <p class="mt-3 text-muted small">I'll automatically check availability and create your request!</p>
                </div>
            </div>

            <div class="chat-input-area">
                <div class="chat-input-wrapper">
                    <input type="text" class="chat-input" id="messageInput" placeholder="e.g., I need 2 units of A+ blood urgently at City Hospital" maxlength="1000">
                    <button class="send-btn" id="sendBtn" onclick="sendMessage()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i>
                Requests are created automatically when stock is available
            </small>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chatMessages = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');

    // Enable send on Enter
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendQuickMessage(message) {
        messageInput.value = message;
        sendMessage();
    }

    function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;

        messageInput.value = '';
        const welcome = chatMessages.querySelector('.welcome-message');
        if (welcome) welcome.remove();

        addMessage(message, 'user');
        showTypingIndicator();
        sendBtn.disabled = true;

        fetch('{{ route("ai.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message: message,
                action: 'request'
            })
        })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();
            sendBtn.disabled = false;

            if (data.success) {
                handleResponse(data);
            } else {
                addMessage('Sorry, something went wrong. Please try again.', 'assistant');
            }
        })
        .catch(error => {
            hideTypingIndicator();
            sendBtn.disabled = false;
            addMessage('Connection error. Please try again.', 'assistant');
            console.error('Error:', error);
        });
    }

    function handleResponse(data) {
        let html = '';

        switch(data.type) {
            case 'request_created':
                html = formatRequestCreated(data);
                break;
            case 'no_stock':
                html = formatNoStock(data);
                break;
            case 'insufficient_stock':
                html = formatInsufficientStock(data);
                break;
            case 'auth_required':
                html = formatAuthRequired(data);
                break;
            case 'stock_info':
                html = formatStockInfo(data);
                break;
            case 'donor_info':
                html = formatDonorInfo(data);
                break;
            default:
                html = data.message || 'How can I help you?';
        }

        addMessage(html, 'assistant', true);
    }

    function formatRequestCreated(data) {
        const req = data.request;
        const urgencyIcon = req.urgency === 'Critical' ? '🚨' : (req.urgency === 'Urgent' ? '⚡' : '✅');
        
        let html = `<p>${urgencyIcon} <strong>Request Created Successfully!</strong></p>`;
        html += `<div class="request-card ${req.urgency.toLowerCase()}">`;
        html += `<p class="mb-2"><strong>Request #${req.id}</strong></p>`;
        html += `<p class="mb-1">🩸 Blood Group: <strong>${req.blood_group}</strong></p>`;
        html += `<p class="mb-1">📦 Units: <strong>${req.units}</strong></p>`;
        html += `<p class="mb-1">🏥 Hospital: ${req.hospital}</p>`;
        html += `<p class="mb-1">⚡ Urgency: ${req.urgency}</p>`;
        html += `<p class="mb-0">📊 Status: <span class="badge bg-warning">Pending Approval</span></p>`;
        html += `</div>`;
        html += `<p class="mt-2 small text-muted">Stock after approval: ${data.stock_status.remaining_after} units remaining</p>`;
        html += `<div class="action-buttons">`;
        html += `<a href="{{ route('user.requests.index') }}" class="btn btn-primary btn-sm"><i class="fas fa-list"></i> View My Requests</a>`;
        html += `</div>`;
        return html;
    }

    function formatNoStock(data) {
        const d = data.details;
        let html = `<p>❌ <strong>No Stock Available</strong></p>`;
        html += `<div class="stock-alert critical">`;
        html += `<p class="mb-2">Unfortunately, we have <strong>no ${d.blood_group} blood</strong> in stock right now.</p>`;
        html += `<p class="mb-0 small">Your request: ${d.units} unit(s) at ${d.hospital || 'your location'}</p>`;
        html += `</div>`;
        html += `<p class="mt-2">💡 <strong>Options:</strong></p>`;
        html += `<ul class="small">`;
        html += `<li>Try nearby blood banks</li>`;
        html += `<li>Contact emergency services if critical</li>`;
        html += `<li>We'll notify you when stock arrives</li>`;
        html += `</ul>`;
        html += `<div class="action-buttons">`;
        html += `<a href="{{ route('user.donors') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-users"></i> Find Compatible Donors</a>`;
        html += `</div>`;
        return html;
    }

    function formatInsufficientStock(data) {
        const d = data.details;
        const shortage = data.stock_status.shortage;
        
        let html = `<p>⚠️ <strong>Insufficient Stock</strong></p>`;
        html += `<div class="stock-alert">`;
        html += `<p class="mb-1">You need: <strong>${d.units} units</strong> of ${d.blood_group}</p>`;
        html += `<p class="mb-1">Available: <strong>${data.stock_status.available} units</strong></p>`;
        html += `<p class="mb-0 text-danger">Shortage: <strong>${shortage} units</strong></p>`;
        html += `</div>`;
        html += `<p class="mt-2">You can request the available <strong>${data.partial_units} units</strong> now:</p>`;
        html += `<div class="action-buttons">`;
        html += `<button class="btn btn-primary btn-sm" onclick="requestPartial('${d.blood_group}', ${data.partial_units}, '${d.hospital || ''}', '${d.urgency}')">`;
        html += `<i class="fas fa-check"></i> Request ${data.partial_units} Unit(s) Now`;
        html += `</button>`;
        html += `<button class="btn btn-outline-secondary btn-sm" onclick="sendQuickMessage('I want to wait for full stock of ${d.units} units ${d.blood_group}')">`;
        html += `<i class="fas fa-clock"></i> Wait for Full Stock`;
        html += `</button>`;
        html += `</div>`;
        return html;
    }

    function formatAuthRequired(data) {
        let html = `<p>🔐 <strong>Login Required</strong></p>`;
        html += `<p>I understood your request for <strong>${data.details.units} unit(s) of ${data.details.blood_group}</strong>.</p>`;
        html += `<p>Please login to create a blood request.</p>`;
        html += `<div class="action-buttons">`;
        html += `<a href="{{ route('user.login') }}" class="btn btn-primary btn-sm"><i class="fas fa-sign-in-alt"></i> Login</a>`;
        html += `<a href="{{ route('user.register') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-user-plus"></i> Register</a>`;
        html += `</div>`;
        return html;
    }

    function formatStockInfo(data) {
        let html = `<p>📊 <strong>Current Blood Stock</strong></p>`;
        html += `<div class="stock-grid">`;
        
        for (const [group, info] of Object.entries(data.stocks)) {
            const statusClass = info.quantity > 5 ? 'available' : (info.quantity > 0 ? 'limited' : 'empty');
            const icon = info.quantity > 5 ? '✅' : (info.quantity > 0 ? '⚠️' : '❌');
            html += `<div class="stock-item ${statusClass}">`;
            html += `<div class="group">${icon} ${group}</div>`;
            html += `<div class="units">${info.quantity} units</div>`;
            html += `</div>`;
        }
        
        html += `</div>`;
        
        if (data.critical.length > 0) {
            html += `<p class="mt-2 small text-danger"><i class="fas fa-exclamation-triangle"></i> Critical: ${data.critical.join(', ')} - No stock!</p>`;
        }
        
        html += `<div class="action-buttons">`;
        html += `<button class="btn btn-outline-primary btn-sm" onclick="sendQuickMessage('I need blood')">Make a Request</button>`;
        html += `</div>`;
        return html;
    }

    function formatDonorInfo(data) {
        let html = `<p>👥 <strong>${data.donors_count} Available Donors</strong>`;
        if (data.blood_group) {
            html += ` for ${data.blood_group}`;
        }
        html += `</p>`;
        html += `<p class="small text-muted">Found ${data.donors_count} compatible donor(s) in our system.</p>`;
        html += `<div class="action-buttons">`;
        html += `<a href="{{ route('user.donors') }}" class="btn btn-primary btn-sm"><i class="fas fa-users"></i> View All Donors</a>`;
        html += `<a href="{{ route('user.find-nearby') }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-map-marker-alt"></i> Find Nearby</a>`;
        html += `</div>`;
        return html;
    }

    function requestPartial(bloodGroup, units, hospital, urgency) {
        // Create partial request
        fetch('{{ route("ai.partial-request") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                blood_group: bloodGroup,
                units: units,
                hospital: hospital,
                urgency: urgency
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const html = formatRequestCreated({
                    request: data.request,
                    stock_status: { remaining_after: 'N/A' }
                });
                addMessage(html, 'assistant', true);
            } else {
                addMessage('Failed to create request. Please try again.', 'assistant');
            }
        })
        .catch(error => {
            addMessage('Connection error. Please try again.', 'assistant');
        });
    }

    function addMessage(content, type, isHtml = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message ' + type;

        const avatarDiv = document.createElement('div');
        avatarDiv.className = 'message-avatar';
        avatarDiv.innerHTML = type === 'user' ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>';

        const contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        if (isHtml) {
            contentDiv.innerHTML = content;
        } else {
            contentDiv.textContent = content;
        }

        messageDiv.appendChild(avatarDiv);
        messageDiv.appendChild(contentDiv);
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function showTypingIndicator() {
        const indicatorDiv = document.createElement('div');
        indicatorDiv.className = 'message assistant';
        indicatorDiv.id = 'typingIndicator';

        const avatarDiv = document.createElement('div');
        avatarDiv.className = 'message-avatar';
        avatarDiv.innerHTML = '<i class="fas fa-robot"></i>';

        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator show';
        typingDiv.innerHTML = '<span></span><span></span><span></span>';

        indicatorDiv.appendChild(avatarDiv);
        indicatorDiv.appendChild(typingDiv);
        chatMessages.appendChild(indicatorDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function hideTypingIndicator() {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) indicator.remove();
    }
</script>
@endpush
