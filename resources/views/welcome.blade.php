@extends('layouts.app')

@section('title', 'AdivoQ - Financial OS for Creators | India & UAE')

@section('content')
    <!-- Background Orbs -->
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Hero Section -->
    @include('partials.hero')

    <!-- Features Section -->
    @include('partials.features')

    <!-- How It Works -->
    @include('partials.how-it-works')

    <!-- Pricing Section -->
    @include('partials.pricing')

    <!-- Testimonials -->
    @include('partials.testimonials')

    <!-- FAQ Section -->
    @include('partials.faq')

    <!-- CTA Section -->
    @include('partials.cta')

    <!-- Footer -->
    @include('partials.footer')

    <!-- Waitlist Modal -->
    @include('partials.waitlist-modal')

    <!-- WhatsApp Popup -->
    @include('partials.whatsapp-popup')

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
@endsection