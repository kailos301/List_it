<div class="col-lg-4 col-xl-3">
  <div class="widget-offcanvas offcanvas-lg offcanvas-start" tabindex="-1" id="widgetOffcanvas"
    aria-labelledby="widgetOffcanvas">
    <div class="offcanvas-header px-20">
      <h4 class="offcanvas-title">{{ __('Filter') }}</h4>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#widgetOffcanvas"
        aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-3 p-lg-0">
      <aside class="widget-area" data-aos="fade-up">
        <form action="{{ route('shop.products') }}" method="get" id="searchForm">
          <div class="widget p-0 mb-40">
            <h5 class="title">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#input"
                aria-expanded="true" aria-controls="input">
                {{ __('Product Name') }}
              </button>
            </h5>
            <div id="input" class="collapse show">
              <div class="accordion-body scroll-y mt-20">
                <input type="text" name="product_name" value="{{ request()->input('product_name') }}"
                  placeholder="{{ __('Search by Title') }}" id="searchByProductName" class="form-control">
              </div>
            </div>
          </div>
          <div class="widget p-0 mb-40">
            <h5 class="title">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#brands"
                aria-expanded="true" aria-controls="brands">
                {{ __('Categories') }}
              </button>
            </h5>
            <div id="brands" class="collapse show">
              <div class="accordion-body scroll-y mt-20">
                <ul class="list-group custom-radio">
                  <li>
                    <input class="input-radio" type="radio" onclick="document.getElementById('searchForm').submit()"
                      name="category" id="radio1" value=""
                      {{ empty(request()->input('category')) ? 'checked' : '' }}>
                    <label class="form-radio-label" for="radio1"><span>{{ __('All') }}</span><span
                        class="qty">({{ $total_products }})</span></label>
                  </li>
                  @foreach ($categories as $category)
                    <li>
                      <input class="input-radio" type="radio" onclick="document.getElementById('searchForm').submit()"
                        name="category" id="radio1-{{ $loop->iteration }}" value="{{ $category->slug }}"
                        {{ request()->input('category') == $category->slug ? 'checked' : '' }}>
                      <label class="form-radio-label"
                        for="radio1-{{ $loop->iteration }}"><span>{{ $category->name }}</span>
                        <span class="qty">({{ $category->products()->get()->count() }})</span>
                      </label>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
          <div class="widget widget-price p-0 mb-40">
            <h5 class="title">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#price"
                aria-expanded="true" aria-controls="price">
                {{ __('Pricing') }}
              </button>
            </h5>
            <div id="price" class="collapse show">
              <div class="accordion-body scroll-y mt-20">
                <div class="row gx-sm-3 d-none">
                  <div class="col-md-6">
                    <div class="form-group mb-30">
                      <input class="form-control" type="hidden"
                        value="{{ request()->filled('min') ? request()->input('min') : $min }}" name="min"
                        id="min">
                      <input class="form-control" type="hidden" value="{{ $min }}" id="o_min">
                      <input class="form-control" type="hidden" value="{{ $max }}" id="o_max">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group mb-30">
                      <input class="form-control"
                        value="{{ request()->filled('max') ? request()->input('max') : $max }}" type="hidden"
                        name="max" id="max">
                    </div>
                  </div>
                </div>
                <input type="hidden" id="currency_symbol" value="{{ $basicInfo->base_currency_symbol }}">
                <div class="price-item mt-10">
                  <div class="price-slider" data-range-slider='filterPriceSlider'></div>
                  <div class="price-value">
                    <span class="color-dark">{{ __('Price') }}:
                      <span class="filter-price-range" data-range-value='filterPriceSliderValue'></span>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="widget widget-ratings p-0 mb-40">
            <h5 class="title">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ratings"
                aria-expanded="true" aria-controls="ratings">
                {{ __('Ratings') }}
              </button>
            </h5>
            <div id="ratings" class="collapse show">
              <div class="accordion-body scroll-y mt-20">
                <ul class="list-group custom-radio">
                  <li>
                    <input class="input-radio" type="radio"
                      onclick="document.getElementById('searchForm').submit()" name="rating" id="radioR"
                      value="" {{ empty(request()->input('rating')) ? 'checked' : '' }}>
                    <label class="form-radio-label" for="radioR"><span>{{ __('All') }}</span></label>
                  </li>

                  <li>
                    <input class="input-radio" type="radio"
                      onclick="document.getElementById('searchForm').submit()" name="rating" id="radioR-5"
                      value="5" {{ request()->input('rating') == 5 ? 'checked' : '' }}>
                    <label class="form-radio-label" for="radioR-5"><span>{{ __('5 stars') }} </span>
                    </label>
                  </li>
                  <li>
                    <input class="input-radio" type="radio"
                      onclick="document.getElementById('searchForm').submit()" name="rating" id="radioR-4"
                      value="4" {{ request()->input('rating') == 4 ? 'checked' : '' }}>
                    <label class="form-radio-label" for="radioR-4"><span>{{ __('4 stars and higher') }}</span>
                    </label>
                  </li>

                  <li>
                    <input class="input-radio" type="radio"
                      onclick="document.getElementById('searchForm').submit()" name="rating" id="radioR-3"
                      value="3" {{ request()->input('rating') == 3 ? 'checked' : '' }}>
                    <label class="form-radio-label" for="radioR-3"><span>{{ __('3 stars and higher') }}</span>
                    </label>
                  </li>

                  <li>
                    <input class="input-radio" type="radio"
                      onclick="document.getElementById('searchForm').submit()" name="rating" id="radioR-2"
                      value="2" {{ request()->input('rating') == 2 ? 'checked' : '' }}>
                    <label class="form-radio-label" for="radioR-2"><span>{{ __('2 stars and higher') }}</span>
                    </label>
                  </li>

                  <li>
                    <input class="input-radio" type="radio"
                      onclick="document.getElementById('searchForm').submit()" name="rating" id="radioR-1"
                      value="1" {{ request()->input('rating') == 1 ? 'checked' : '' }}>
                    <label class="form-radio-label" for="radioR-1"><span>{{ __('1 star and higher') }}</span>
                    </label>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="cta">
            <a href="{{ route('shop.products') }}" class="btn btn-lg btn-primary icon-start w-100"><i
                class="fal fa-sync-alt"></i>{{ __('Reset All') }}</a>
          </div>
        </form>
        <!-- Spacer -->
        <div class="pb-40"></div>
      </aside>

    </div>
    @if (!empty(showAd(1)))
      <div class="text-center mt-4">
        {!! showAd(1) !!}
      </div>
    @endif
  </div>
</div>
