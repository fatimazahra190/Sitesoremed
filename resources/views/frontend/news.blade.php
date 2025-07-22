@extends('layouts.app')
@section('content')

<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #388e3c 0%, #66bb6a 100%); color: white; padding: 80px 0; text-align: center;">
    <div class="container">
        <h1 class="display-4 mb-4">Latest News</h1>
        <p class="lead">Stay updated with our latest events, press releases, and company updates</p>
    </div>
</section>

<!-- News Section -->
<section class="py-5">
    <div class="container">
        @if($news->count() > 0)
            <div class="row">
                @foreach($news as $article)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="mb-3">
                                    <span class="badge bg-success">{{ $article->published_at->format('M d, Y') }}</span>
                                </div>
                                <h5 class="card-title" style="color: #388e3c;">{{ $article->title }}</h5>
                                <p class="card-text">
                                    {{ Str::limit($article->content, 150) }}
                                </p>
                                <div class="mt-auto">
                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#newsModal{{ $article->id }}">
                                        Read More
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- News Modal -->
                    <div class="modal fade" id="newsModal{{ $article->id }}" tabindex="-1" aria-labelledby="newsModalLabel{{ $article->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="newsModalLabel{{ $article->id }}">{{ $article->title }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> Published on {{ $article->published_at->format('F d, Y \a\t g:i A') }}
                                        </small>
                                    </div>
                                    <div style="white-space: pre-wrap;">{{ $article->content }}</div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row mt-4">
                <div class="col-12">
                    <nav aria-label="News pagination">
                        {{ $news->links() }}
                    </nav>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-newspaper fa-4x" style="color: #388e3c;"></i>
                </div>
                <h3 style="color: #388e3c;">No News Available</h3>
                <p class="text-muted">Check back later for the latest updates and announcements.</p>
            </div>
        @endif
    </div>
</section>

<!-- Newsletter Signup -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h3 style="color: #388e3c;">Stay Updated</h3>
                <p class="lead mb-4">Subscribe to our newsletter to receive the latest news and updates directly in your inbox.</p>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Enter your email address">
                            <button class="btn btn-success" type="button">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection 