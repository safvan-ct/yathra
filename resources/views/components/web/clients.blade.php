@props(['partners'])

<div class="container">
    <div class="text-center mb-3">
        <h6 class="text-gold-uae fw-bold text-uppercase">Trusted Affiliations</h6>
        <h2 class="fw-bold">Authorized Service Partners</h2>
    </div>

    <div class="swiper partnerSwiper py-3">
        <div class="swiper-wrapper align-items-center">

            @foreach ($partners as $client)
                <div class="swiper-slide text-center">
                    <div class="partner-logo-box">
                        <img src="{{ $client->image_src }}" alt="MOHRE" class="img-thumbnail">
                        <p class="small mt-2 fw-bold text-muted text-uppercase">{{ $client->name }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination mt-4"></div>
    </div>
</div>
