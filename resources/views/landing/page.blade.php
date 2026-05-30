@extends('landing.layout')

@section('content')
    <section class="placeholder">
        <div class="container">
            <p class="eyebrow">Page In Progress</p>
            <h2>{{ $heading ?? 'Page' }}</h2>
            <p>{{ $description ?? 'This page will be available soon.' }}</p>
        </div>
    </section>
@endsection

@push('head')
    <style>
        .placeholder {
            min-height: calc(100vh - 250px);
            display: grid;
            place-items: center;
            text-align: center;
            background: linear-gradient(180deg, #f7fbf2 0%, #ffffff 100%);
            padding: 60px 0;
        }

        .placeholder .container {
            max-width: 760px;
        }

        .placeholder .eyebrow {
            margin: 0 0 14px;
            color: #4e9b12;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            font-weight: 800;
            font-size: 0.78rem;
        }

        .placeholder h2 {
            margin: 0;
            font-size: clamp(2rem, 3.7vw, 3.5rem);
            line-height: 1.15;
            color: #17310d;
        }

        .placeholder p {
            margin: 16px auto 0;
            color: #52614a;
            line-height: 1.75;
            font-size: 1rem;
        }
    </style>
@endpush
