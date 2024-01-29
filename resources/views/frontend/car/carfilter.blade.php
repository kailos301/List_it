              <!-- Brands -->
                <div class="widget widget-ratings p-1 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ratings"
                        aria-expanded="true" aria-controls="ratings">
                        {{ __('Brands') }} 
                      </button>
                    </h5>
                    <div id="ratings" class="collapse show">
                      <div class="accordion-body scroll-y mt-20">
                        <ul class="list-group custom-checkbox">
                          @php
                            if (!empty(request()->input('brands'))) {
                                $selected_brands = [];
                                if (is_array(request()->input('brands'))) {
                                    $selected_brands = request()->input('brands');
                                } else {
                                    array_push($selected_brands, request()->input('brands'));
                                }
                            } else {
                                $selected_brands = [];
                            }
                          @endphp

                          <select class="form-select form-control js-example-basic-single1" onchange="updateUrl()" name="brands[]">
                                <option value="">{{ __('Select Make') }}</option>
                               @foreach ($brands as $brand)
                                  <option {{ in_array($brand->slug, $selected_brands) ? 'selected' : '' }}
                                    value="{{ $brand->slug }}">{{ $brand->name }}</option>
                                @endforeach
                          </select>

                          <!-- @foreach ($brands as $brand)
                            <li class="form-check-inline">
                              <input class="input-checkbox" type="checkbox" name="brands[]"
                                id="checkbox{{ $brand->id }}" value="{{ $brand->slug }}"
                                {{ in_array($brand->slug, $selected_brands) ? 'checked' : '' }} onchange="updateUrl()">

                              <label class="form-check-label"
                                for="checkbox{{ $brand->id }}"><span>{{ $brand->name }}</span></label>
                            </li>
                          @endforeach -->
                        </ul>
                      </div>
                    </div>
                  </div>
                  <!-- Models -->
                  @if (request()->filled('brands'))
                    <div class="widget widget-ratings p-0 mb-20">
                      <h5 class="title">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                          data-bs-target="#models" aria-expanded="true" aria-controls="models">
                          {{ __('Models') }}
                        </button>
                      </h5>
                      @php
                        $selected_brands = request()->input('brands');
                        if (is_array($selected_brands)) {
                            $selected_brands = $selected_brands;
                        } else {
                            $selected_brands = [$selected_brands];
                        }
                      @endphp
                      <div id="models" class="collapse show">
                        <div class="accordion-body scroll-y mt-20">
                          <ul class="list-group custom-checkbox">
                            @php
                              if (!empty(request()->input('models'))) {
                                  $selected_models = [];
                                  if (is_array(request()->input('models'))) {
                                      $selected_models = request()->input('models');
                                  } else {
                                      array_push($selected_models, request()->input('models'));
                                  }
                              } else {
                                  $selected_models = [];
                              }
                            @endphp
                            @foreach ($selected_brands as $selected_brand)
                              @php
                                $s_brand = App\Models\Car\Brand::where('slug', $selected_brand)->first();
                                if ($s_brand) {
                                    $models = App\Models\Car\CarModel::where([['brand_id', $s_brand->id], ['status', 1]])->get();
                                } else {
                                    $models = [];
                                }
                              @endphp
                              

                              <select class="form-select form-control js-example-basic-single1" onchange="updateUrl()" name="models[]">
                                <option value="">{{ __('Select Model') }}</option>
                               @foreach ($models as $model)
                                  <option {{ in_array($model->slug, $selected_models) ? 'selected' : '' }}
                                    value="{{ $model->slug }}">{{ $model->name }}</option>
                                @endforeach
                               </select>
                                <!-- <li>
                                  <input class="input-checkbox" type="checkbox" name="models[]"
                                    id="checkbox{{ $model->id }}"
                                    {{ in_array($model->slug, $selected_models) ? 'checked' : '' }}
                                    value="{{ $model->slug }}" onchange="updateUrl()">

                                  <label class="form-check-label"
                                    for="checkbox{{ $model->id }}"><span>{{ $model->name }}</span></label>
                                </li> 
                              -->
                            @endforeach
                          </ul>
                        </div>
                      </div>
                    </div>
                 @endif
                  <!-- Year -->
                  <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#select3" aria-expanded="true" aria-controls="select">
                        {{ __('Year') }}
                      </button>
                    </h5>
                    <div id="select3" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                             <div class="col-6 float-start"float-start> 
                              <select class="form-select form-control js-example-basic-single1" onchange="updateUrl()" name="year_min">
                                <option value="">{{ __('Min Year') }}</option>
                                @foreach ($caryear as $year)
                                  <option 
                                    value="{{ $year->name }}" @selected(request()->input('year_min') == $year->name)>{{ $year->name }}</option>
                                @endforeach
                              </select>

                             
                            </div>
                        <div class="col-6 float-end">
                        <select class="form-select form-control js-example-basic-single1" onchange="updateUrl()" name="year_max">
                                <option value="">{{ __('Max Year') }}</option>
                                @foreach ($caryear as $year)
                                  <option 
                                    value="{{ $year->name }}" @selected(request()->input('year_max') == $year->name)>{{ $year->name }}</option>
                                @endforeach
                              </select>
                            </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                 
                  <!-- Mileage -->
                  <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#select3" aria-expanded="true" aria-controls="select">
                        {{ __('Mileage (Miles)') }}
                      </button>
                    </h5>
                    <div id="select3" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                             <div class="col-6 float-start"float-start> 
                              <select class="form-select form-control js-example-basic-single1" onchange="updateUrl()" name="mileage_min">
                                <option value="">{{ __('Min ') }}</option>
                               @foreach ($adsmileage as $mileage)
                                  <option 
                                    value="{{ $mileage->name }}" @selected(request()->input('mileage_min') == $mileage->name)>{{ ($mileage->name) }}</option>
                                @endforeach
                              </select>

                             
                            </div>
                        <div class="col-6 float-end">
                        <select class="form-select form-control js-example-basic-single1" onchange="updateUrl()" name="mileage_max">
                                <option value="">{{ __('Max ') }}</option>
                                @foreach ($adsmileage as $mileage)
                                  <option 
                                    value="{{ $mileage->name }}" @selected(request()->input('mileage_max') == $mileage->name)>{{ ($mileage->name) }}</option>
                                @endforeach
                              </select>
                            </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
               
                  <!-- Fuel Types -->
                  <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#select3" aria-expanded="true" aria-controls="select">
                        {{ __('Fuel Types') }}
                      </button>
                    </h5>
                    <div id="select3" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row gx-sm-3">
                          <div class="col-xl-12">
                            <div class="form-group">
                              <select class="form-select form-control" onchange="updateUrl()" name="fuel_type">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($fuel_types as $fuel_type)
                                  <option {{ request()->input('fuel_type') == $fuel_type->slug ? 'selected' : '' }}
                                    value="{{ $fuel_type->slug }}">{{ $fuel_type->name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                    <!-- Transmission Types -->
                  <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#transmission" aria-expanded="true" aria-controls="transmission">
                        {{ __('Transmission Types') }}
                      </button>
                    </h5>
                    <div id="transmission" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row gx-sm-3">
                          <div class="col-xl-12">
                            <div class="form-group">
                              <select class="form-select form-control" name="transmission" onchange="updateUrl()">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($transmission_types as $transmission_type)
                                  <option
                                    {{ request()->input('transmission') == $transmission_type->slug ? 'selected' : '' }}
                                    value="{{ $transmission_type->slug }}">{{ $transmission_type->name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                   <!-- Colour -->
                  <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#select2" aria-expanded="true" aria-controls="select">
                        {{ __('Colour') }}
                      </button>
                    </h5>
                    <div id="select2" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row gx-sm-3">
                          <div class="col-12">
                            <div class="form-group">
                              <select class="form-select form-control js-example-basic-single1" name="condition" onchange="updateUrl()">
                                <option value="">{{ __('All') }}</option>
                                @foreach ($car_conditions as $car_condition)
                                  <option {{ request()->input('condition') == $car_condition->slug ? 'selected' : '' }}
                                    value="{{ $car_condition->slug }}">{{ $car_condition->name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                   <!-- Number of owners -->
              <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#select3" aria-expanded="true" aria-controls="select">
                        {{ __('Number of owners') }}
                      </button>
                    </h5>
                    <div id="select3" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                             <div class="col-12 float-start"float-start> 
                              <select class="form-select form-control" onchange="updateUrl()" name="owners">
                                <option value="">{{ __('Any') }}</option>
                                <option value="1" @selected(request()->input('owners') == 1)>1</option>
                                <option value="2" @selected(request()->input('owners') == 2)>2</option>
                                <option value="3" @selected(request()->input('owners') == 3)>3</option>
                                <option value="4" @selected(request()->input('owners') == 4)>4</option>
                                <option value="5" @selected(request()->input('owners') == 5)>5</option>
                                <option value="6" @selected(request()->input('owners') == 6)>6</option>
                                <option value="7" @selected(request()->input('owners') == 7)>7</option>
                                <option value="8" @selected(request()->input('owners') == 8)>8</option>
                                
                              </select>

                             
                            </div>
                       
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
               </div>
               <!-- Number of doors -->
                <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#select3" aria-expanded="true" aria-controls="select">
                        {{ __('Number of doors') }}
                      </button>
                    </h5>
                    <div id="select3" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                             <div class="col-12 float-start"float-start> 
                              <select class="form-select form-control" onchange="updateUrl()" name="doors">
                                <option value="">{{ __('Any') }}</option>
                                <option value="2" @selected(request()->input('doors') == 2)>2</option>
                                <option value="3" @selected(request()->input('doors') == 3)>3</option>
                                <option value="4" @selected(request()->input('doors') == 4)>4</option>
                                <option value="5" @selected(request()->input('doors') == 5)>5</option>
                                <option value="6" @selected(request()->input('doors') == 6)>6</option>
                                
                              </select>

                             
                            </div>
                       
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
               </div>
                <!-- Number of Seats -->
              <div class="widget widget-select p-0 mb-20">
                    <h5 class="title">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#select3" aria-expanded="true" aria-controls="select">
                        {{ __('Seat count') }}
                      </button>
                    </h5>
                    <div id="select3" class="collapse show">
                      <div class="accordion-body scroll-y">
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                             <div class="col-6 float-start"> 
                              <select class="form-select form-control" onchange="updateUrl()" name="seat_min">
                                <option value="">{{ __('Min') }}</option>
                               <option value="2" @selected(request()->input('seat_min') == 2)>2</option>
                                <option value="3" @selected(request()->input('seat_min') == 3)>3</option>
                                <option value="4" @selected(request()->input('seat_min') == 4)>4</option>
                                <option value="5" @selected(request()->input('seat_min') == 5)>5</option>
                                <option value="6" @selected(request()->input('seat_min') == 6)>6</option>
                                <option value="7" @selected(request()->input('seat_min') == 7)>7</option>
                                <option value="8" @selected(request()->input('seat_min') == 8)>8</option>
                              </select>

                             
                            </div>
                        <div class="col-6 float-end">
                        <select class="form-select form-control" onchange="updateUrl()" name="seat_max">
                                <option value="">{{ __('Max') }}</option>
                              <option value="2" @selected(request()->input('seat_max') == 2)>2</option>
                                <option value="3" @selected(request()->input('seat_max') == 3)>3</option>
                                <option value="4" @selected(request()->input('seat_max') == 4)>4</option>
                                <option value="5" @selected(request()->input('seat_max') == 5)>5</option>
                                <option value="6" @selected(request()->input('seat_max') == 6)>6</option>
                                <option value="7" @selected(request()->input('seat_max') == 7)>7</option>
                                <option value="8" @selected(request()->input('seat_max') == 8)>8</option>
                              </select>
                            </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>