@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="page-header text-center">
                <div class="header-icon mx-auto">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h1 class="page-title">Frequently Asked Questions</h1>
                <p class="page-subtitle">Find answers to common questions about our services</p>
                
                <!-- Search Bar -->
                <div class="search-container mt-4">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" 
                               class="search-input" 
                               id="faqSearch" 
                               placeholder="Search FAQs..."
                               autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Pills -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="category-pills">
                <button class="pill active" data-category="all">All Questions</button>
                <button class="pill" data-category="general">General</button>
                <button class="pill" data-category="account">Account</button>
                <button class="pill" data-category="requests">Requests</button>
                <button class="pill" data-category="payments">Payments</button>
                <button class="pill" data-category="technical">Technical</button>
            </div>
        </div>
    </div>

    <!-- FAQ Accordion -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            @forelse($faqs as $index => $faq)
                <div class="faq-card" data-category="general">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <div class="question-content">
                            <span class="question-icon">
                                <i class="fas fa-question-circle"></i>
                            </span>
                            <h3 class="question-text">{{ $faq->question }}</h3>
                        </div>
                        <div class="question-toggle">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                    <div class="faq-answer" style="display: {{ $index == 0 ? 'block' : 'none' }};">
                        <div class="answer-content">
                            <span class="answer-icon">
                                <i class="fas fa-lightbulb"></i>
                            </span>
                            <div class="answer-text">
                                {{ $faq->answer }}
                            </div>
                        </div>
                        
                        <!-- Answer Footer -->
                        <div class="answer-footer">
                            <span class="helpful-text">Was this helpful?</span>
                            <div class="helpful-buttons">
                                <button class="helpful-btn yes" onclick="markHelpful(this, true)">
                                    <i class="fas fa-thumbs-up me-1"></i>
                                    Yes
                                </button>
                                <button class="helpful-btn no" onclick="markHelpful(this, false)">
                                    <i class="fas fa-thumbs-down me-1"></i>
                                    No
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h3>No FAQs Available</h3>
                    <p>We're currently updating our FAQ section. Please check back later.</p>
                    <a href="{{ route('requests.create') }}" class="btn-contact">
                        <i class="fas fa-envelope me-2"></i>
                        Contact Support
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Still Have Questions Section -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Still have questions?</h3>
                <p>Can't find the answer you're looking for? Please chat with our friendly team.</p>
                <div class="contact-buttons">
                    <a href="{{ route('requests.create') }}" class="contact-btn primary">
                        <i class="fas fa-paper-plane me-2"></i>
                        Submit a Request
                    </a>
                    <a href="{{ route('messages.index') }}" class="contact-btn secondary">
                        <i class="fas fa-comment-dots me-2"></i>
                        Live Chat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--dark-card) 0%, var(--dark-secondary) 100%);
        border: 1px solid var(--border-dark);
        border-radius: 30px;
        padding: 3rem 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.1), transparent);
        border-radius: 50%;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: -50px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.1), transparent);
        border-radius: 50%;
    }

    .header-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 2.5rem;
        color: white;
        box-shadow: 0 15px 30px rgba(249, 115, 22, 0.3);
        transform: rotate(0deg);
        transition: all 0.5s ease;
    }

    .page-header:hover .header-icon {
        transform: rotate(360deg);
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Search Bar */
    .search-container {
        max-width: 500px;
        margin: 0 auto;
    }

    .search-wrapper {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--orange-primary);
        font-size: 1.2rem;
        z-index: 1;
    }

    .search-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3.5rem;
        background: var(--input-bg);
        border: 2px solid var(--border-dark);
        border-radius: 50px;
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--orange-primary);
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
    }

    .search-input::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }

    /* Category Pills */
    .category-pills {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem;
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 50px;
    }

    .pill {
        padding: 0.6rem 1.5rem;
        background: transparent;
        border: 1px solid var(--border-dark);
        border-radius: 30px;
        color: var(--text-secondary);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .pill:hover {
        border-color: var(--orange-primary);
        color: var(--orange-primary);
        transform: translateY(-2px);
    }

    .pill.active {
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border-color: var(--orange-primary);
        color: white;
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.3);
    }

    /* FAQ Cards */
    .faq-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
        animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .faq-card:hover {
        border-color: var(--orange-primary);
        box-shadow: 0 10px 30px rgba(249, 115, 22, 0.1);
    }

    .faq-question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .faq-question:hover {
        background: rgba(249, 115, 22, 0.05);
    }

    .question-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .question-icon {
        width: 40px;
        height: 40px;
        background: rgba(249, 115, 22, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--orange-primary);
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .faq-card:hover .question-icon {
        background: var(--orange-primary);
        color: white;
        transform: rotate(360deg);
    }

    .question-text {
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        line-height: 1.4;
    }

    .question-toggle {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        transition: all 0.3s ease;
    }

    .faq-question[aria-expanded="true"] .question-toggle i {
        transform: rotate(180deg);
        color: var(--orange-primary);
    }

    /* FAQ Answer */
    .faq-answer {
        padding: 0 1.5rem 1.5rem 1.5rem;
    }

    .answer-content {
        display: flex;
        gap: 1rem;
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 15px;
        border: 1px solid var(--border-dark);
    }

    .answer-icon {
        width: 40px;
        height: 40px;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #10b981;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .answer-text {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.6;
    }

    /* Answer Footer */
    .answer-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
    }

    .helpful-text {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .helpful-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .helpful-btn {
        padding: 0.4rem 1rem;
        background: transparent;
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        color: var(--text-secondary);
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .helpful-btn.yes:hover {
        background: #10b981;
        border-color: #10b981;
        color: white;
        transform: translateY(-2px);
    }

    .helpful-btn.no:hover {
        background: #ef4444;
        border-color: #ef4444;
        color: white;
        transform: translateY(-2px);
    }

    .helpful-btn.active {
        background: var(--orange-primary);
        border-color: var(--orange-primary);
        color: white;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 30px;
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        background: rgba(249, 115, 22, 0.1);
        border: 2px dashed var(--orange-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 3.5rem;
        color: var(--orange-primary);
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty-state h3 {
        color: var(--text-primary);
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: 2rem;
    }

    .btn-contact {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        border: none;
        border-radius: 30px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
    }

    .btn-contact:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
    }

    /* Contact Card */
    .contact-card {
        background: linear-gradient(135deg, var(--dark-card) 0%, var(--dark-secondary) 100%);
        border: 1px solid var(--border-dark);
        border-radius: 30px;
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .contact-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.1), transparent);
        border-radius: 50%;
    }

    .contact-icon {
        width: 80px;
        height: 80px;
        background: rgba(249, 115, 22, 0.1);
        border: 2px solid var(--orange-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        color: var(--orange-primary);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4);
        }
        70% {
            box-shadow: 0 0 0 20px rgba(249, 115, 22, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(249, 115, 22, 0);
        }
    }

    .contact-card h3 {
        color: var(--text-primary);
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }

    .contact-card p {
        color: var(--text-secondary);
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    .contact-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .contact-btn {
        padding: 0.75rem 2rem;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .contact-btn.primary {
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        color: white;
        box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
    }

    .contact-btn.primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
    }

    .contact-btn.secondary {
        background: transparent;
        border: 2px solid var(--orange-primary);
        color: var(--orange-primary);
    }

    .contact-btn.secondary:hover {
        background: var(--orange-primary);
        color: white;
        transform: translateY(-3px);
    }

    /* Animation for search results */
    .faq-card.hidden {
        display: none;
    }

    .faq-card.highlight {
        animation: highlight 1s ease;
    }

    @keyframes highlight {
        0%, 100% {
            border-color: var(--border-dark);
        }
        50% {
            border-color: var(--orange-primary);
            box-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
        }
    }

    /* Text visibility enhancements */
    .question-text,
    .answer-text,
    .contact-card h3,
    .empty-state h3,
    .page-title {
        color: var(--text-primary) !important;
    }

    .page-subtitle,
    .answer-text,
    .contact-card p,
    .empty-state p,
    .helpful-text {
        color: var(--text-secondary) !important;
    }

    .search-input,
    .pill,
    .helpful-btn {
        color: var(--text-primary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 2rem 1rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .category-pills {
            border-radius: 20px;
        }
        
        .pill {
            padding: 0.4rem 1rem;
            font-size: 0.9rem;
        }
        
        .question-text {
            font-size: 1rem;
        }
        
        .answer-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .answer-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
        
        .contact-buttons {
            flex-direction: column;
        }
        
        .contact-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle FAQ answer
    function toggleFaq(element) {
        const faqCard = element.closest('.faq-card');
        const answer = faqCard.querySelector('.faq-answer');
        const toggleIcon = element.querySelector('.question-toggle i');
        
        if (answer.style.display === 'none' || answer.style.display === '') {
            // Close all other FAQs
            document.querySelectorAll('.faq-answer').forEach(ans => {
                ans.style.display = 'none';
            });
            document.querySelectorAll('.question-toggle i').            document.querySelectorAll('.question-toggle i').forEach(icon => {
                icon.style.transform = 'rotate(0deg)';
                icon.style.color = 'var(--text-secondary)';
            });
            
            // Open this FAQ
            answer.style.display = 'block';
            toggleIcon.style.transform = 'rotate(180deg)';
            toggleIcon.style.color = 'var(--orange-primary)';
            
            // Smooth scroll to this FAQ
            faqCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            answer.style.display = 'none';
            toggleIcon.style.transform = 'rotate(0deg)';
            toggleIcon.style.color = 'var(--text-secondary)';
        }
    }

    // Search functionality
    const searchInput = document.getElementById('faqSearch');
    const faqCards = document.querySelectorAll('.faq-card');
    const noResultsMessage = document.createElement('div');
    noResultsMessage.className = 'no-results';
    noResultsMessage.innerHTML = `
        <div class="empty-state" style="margin-top: 2rem;">
            <div class="empty-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>No Results Found</h3>
            <p>We couldn't find any FAQs matching your search. Try different keywords or contact support.</p>
            <a href="{{ route('requests.create') }}" class="btn-contact">
                <i class="fas fa-envelope me-2"></i>
                Contact Support
            </a>
        </div>
    `;

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let hasResults = false;
        
        faqCards.forEach(card => {
            const question = card.querySelector('.question-text').textContent.toLowerCase();
            const answer = card.querySelector('.answer-text').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                card.classList.remove('hidden');
                hasResults = true;
                
                // Highlight matching text
                if (searchTerm.length > 0) {
                    card.classList.add('highlight');
                    setTimeout(() => {
                        card.classList.remove('highlight');
                    }, 1000);
                }
            } else {
                card.classList.add('hidden');
            }
        });
        
        // Show/hide no results message
        const faqContainer = document.querySelector('.faq-card').parentNode;
        const existingNoResults = document.querySelector('.no-results');
        
        if (!hasResults && searchTerm.length > 0) {
            if (!existingNoResults) {
                faqContainer.appendChild(noResultsMessage);
            }
        } else {
            if (existingNoResults) {
                existingNoResults.remove();
            }
        }
    });

    // Category filtering
    const categoryPills = document.querySelectorAll('.pill');
    
    categoryPills.forEach(pill => {
        pill.addEventListener('click', function() {
            // Update active pill
            categoryPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            
            // Filter FAQs by category (you'll need to add data-category attributes to your FAQ cards)
            faqCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
            
            // Clear search input
            if (searchInput) {
                searchInput.value = '';
            }
        });
    });

    // Mark helpful/unhelpful
    function markHelpful(button, isHelpful) {
        const buttons = button.parentElement.querySelectorAll('.helpful-btn');
        
        // Remove active class from all buttons in this group
        buttons.forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        button.classList.add('active');
        
        // Show feedback message
        const faqCard = button.closest('.faq-card');
        const question = faqCard.querySelector('.question-text').textContent;
        
        const feedbackMessage = document.createElement('div');
        feedbackMessage.className = 'feedback-message';
        feedbackMessage.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>Thank you for your feedback!</span>
        `;
        
        faqCard.appendChild(feedbackMessage);
        
        setTimeout(() => {
            feedbackMessage.remove();
        }, 3000);
        
        // Here you can add AJAX call to save feedback
        console.log(`FAQ helpful: ${isHelpful ? 'Yes' : 'No'} - Question: ${question}`);
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + F to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            searchInput.focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        }
    });

    // Initialize tooltips
    $(document).ready(function() {
        // Add animation on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });
        
        document.querySelectorAll('.faq-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            observer.observe(card);
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    });

    // Share FAQ
    function shareFaq(question, answer) {
        if (navigator.share) {
            navigator.share({
                title: 'PSRS FAQ',
                text: question + '\n\n' + answer,
                url: window.location.href,
            }).catch(console.error);
        } else {
            // Fallback - copy to clipboard
            const text = `${question}\n\n${answer}`;
            navigator.clipboard.writeText(text).then(() => {
                alert('FAQ copied to clipboard!');
            });
        }
    }

    // Print FAQ
    function printFaq() {
        window.print();
    }

    // Add share and print buttons to each FAQ card
    document.querySelectorAll('.faq-answer').forEach(answer => {
        const footer = answer.querySelector('.answer-footer');
        if (footer) {
            const shareBtn = document.createElement('button');
            shareBtn.className = 'helpful-btn share-btn';
            shareBtn.innerHTML = '<i class="fas fa-share-alt me-1"></i> Share';
            shareBtn.onclick = function() {
                const faqCard = this.closest('.faq-card');
                const question = faqCard.querySelector('.question-text').textContent;
                const answer = faqCard.querySelector('.answer-text').textContent;
                shareFaq(question, answer);
            };
            
            footer.appendChild(shareBtn);
        }
    });

    // Add feedback message styles
    const style = document.createElement('style');
    style.textContent = `
        .feedback-message {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 1rem 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(16, 185, 129, 0.3);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideInRight 0.3s ease;
            z-index: 9999;
        }
        
        .feedback-message i {
            font-size: 1.2rem;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .no-results {
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .share-btn {
            margin-left: auto;
        }
        
        .share-btn:hover {
            background: var(--orange-primary) !important;
            border-color: var(--orange-primary) !important;
            color: white !important;
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
