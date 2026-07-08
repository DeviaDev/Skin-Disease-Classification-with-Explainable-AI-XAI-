<!DOCTYPE html>
<html lang="en">
    <head>
       
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm" id="mainNav">
            <div class="container px-7">
                <a class="navbar-brand fw-bold" href="#page-top">
                    <img src="{{ asset('assets/Logo_nav.png') }}" alt="SyifAI Logo" width="150" height="60" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="bi-list"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto me-4 my-3 my-lg-0">
                        <li class="nav-item"><a class="nav-link me-lg-3" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link me-lg-3" href="{{ route('detection') }}">Detection</a></li>
                    </ul>
                    <button class="btn btn-primary rounded-pill px-3 mb-2 mb-lg-0" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                        <span class="d-flex align-items-center">
                            <i class="bi-chat-text-fill me-2"></i>
                            <span class="small">Send Feedback</span>
                        </span>
                    </button>
                </div>
            </div>
        </nav>
 
                <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-gradient p-4">
                        <h5 class="modal-title font-alt text-white" id="feedbackModalLabel">Send feedback</h5>
                        <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body border-0 p-4">
           
                        <form action="{{ route('feedback.store') }}" method="POST">
                            @csrf

                            <!-- Name -->
                            <div class="form-floating mb-3">
                                <input
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    type="text"
                                    placeholder="Enter your name..."
                                    required>
                                <label for="name">Full name</label>
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    type="email"
                                    placeholder="name@example.com"
                                    required>
                                <label for="email">Email address</label>
                            </div>

                            <!-- Phone -->
                            <div class="form-floating mb-3">
                                <input
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    type="tel"
                                    placeholder="08123456789"
                                    required>
                                <label for="phone">Phone number</label>
                            </div>

                            <!-- Message -->
                            <div class="form-floating mb-3">
                                <textarea
                                    class="form-control"
                                    id="message"
                                    name="message"
                                    placeholder="Enter your message here..."
                                    style="height: 10rem"
                                    required></textarea>
                                <label for="message">Message</label>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary rounded-pill btn-lg" type="submit">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
     
    </body>
</html>
