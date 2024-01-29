@php
  $version = $basicInfo->theme_version;
@endphp
@extends("frontend.layouts.layout-v$version")
@section('pageHeading')
  {{ __('Support Ticket Details') }}
@endsection

@section('content')
  <!-- Page title start-->
  <div
    class="page-title-area ptb-100 bg-img {{ $basicInfo->theme_version == 2 || $basicInfo->theme_version == 3 ? 'has_header_2' : '' }}"
    @if (!empty($bgImg)) data-bg-image="{{ asset('assets/img/' . $bgImg->breadcrumb) }}" @endif
    src="{{ asset('assets/front/imagesplaceholder.png') }}">
    <div class="container">
      <div class="content">
        <h2>
          {{ __('Ticket') . ' #' . $ticket->id }}
        </h2>
        <ul class="list-unstyled">
          <li class="d-inline"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
          <li class="d-inline">/</li>
          <li class="d-inline active opacity-75">{{ __('Support Ticket Details') }}</li>
        </ul>
      </div>
    </div>
  </div>
  <!-- Page title end-->


  <!--====== Start Dashboard Section ======-->
  <div class="user-dashboard pt-100 pb-60">
    <div class="container">
      <div class="row gx-xl-5">
        @includeIf('frontend.user.side-navbar')
        <div class="col-lg-9">
          <div class="account-info radius-md mb-40">
            <div class="title">
              <h4>{{ __('Support Ticket Details') . ' #' }} {{ $ticket->id }}</h4>
              <hr>
              <div class="subject mb-1">
                <h5>{{ $ticket->subject }}</h5>
                <div class="d-flex align-items-center gap-1">
                  @if ($ticket->status == 1)
                    <h6 class="badge bg-info">{{ __('Pending') }}</h6>
                  @elseif ($ticket->status == 2)
                    <h6 class="badge bg-success">{{ __('Open') }}</h6>
                  @else
                    <h6 class="badge bg-danger">{{ __('Closed') }}</h6>
                  @endif
                  <h6><span
                      class="badge bg-dark">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d-M-Y H:s a') }}</span>
                  </h6>

                </div>
              </div>
              <div class="description">
                <p>{!! $ticket->description !!}</p>
              </div>
              @if ($ticket->attachment != null)
                <a href="{{ asset('assets/admin/img/support-ticket/attachment/' . $ticket->attachment) }}"
                  download="{{ __('support_file') }}" class="btn btn-primary">
                  <i class="fas fa-download"></i>
                  {{ __('Download Attachment') }}
                </a>
              @endif
            </div>
            <div class="main-info">
              <hr>
              <div class="message-section">
                <h5>{{ __('Replies') }}</h5>
                <div class="message-lists">
                  <div class="messages">
                    @if (count($ticket->messages) > 0)
                      @foreach ($ticket->messages as $reply)
                        @if ($reply->type == 2)
                          @php
                            $admin = App\Models\Admin::where('id', $reply->user_id)->first();
                          @endphp
                          <div class="single-message mb-30">
                            <div class="user-details">
                              <div class="user-img">
                                <img class="support-user-img"
                                  src="{{ $admin->image ? asset('assets/img/admins/' . $admin->image) : asset('assets/admin/img/propics/blank_user.jpg') }}"
                                  alt="">
                              </div>
                              <div class="user-infos">
                                <h6 class="name">{{ $admin->username }}</h6>
                                <span class="type">
                                  <i class="fas fa-user"></i>
                                  {{ $admin->id == 1 ? __('Super Admin') : $admin->role->name }}
                                </span>
                                <span
                                  class="badge bg-info text-dark">{{ \Carbon\Carbon::parse($reply->created_at)->format('d-M-Y H:s a') }}</span>
                                @if ($reply->file != null)
                                  <a href="{{ asset('assets/admin/img/support-ticket/' . $reply->file) }}"
                                    download="support_file" class="reply-download-btn"><i class="fas fa-download"></i>
                                    {{ __('Download') }}</a>
                                @endif
                              </div>
                            </div>
                            <div class="message">
                              <div class="summernote-content">
                                {!! $reply->reply !!}
                              </div>
                            </div>
                          </div>
                        @else
                          @php
                            $user = App\Models\User::where('id', $ticket->user_id)->first();
                          @endphp
                          <div class="single-message mb-30">
                            <div class="user-details">
                              <div class="user-img">
                                @if ($user->image != null)
                                  <img class="support-user-img" src="{{ asset('assets/img/users/' . $user->image) }}"
                                    alt="">
                                @else
                                  <img class="support-user-img" src="{{ asset('assets/img/blank-user.jpg') }}"
                                    alt="">
                                @endif
                              </div>
                              <div class="user-infos">
                                <h6 class="name">{{ $user->username }}</h6>
                                <span
                                  class="badge bg-info text-dark">{{ \Carbon\Carbon::parse($reply->created_at)->format('d-M-Y H:s a') }}</span>
                                @if ($reply->file != null)
                                  <a href="{{ asset('assets/admin/img/support-ticket/' . $reply->file) }}"
                                    download="support_file.zip" class="reply-download-btn"><i class="fas fa-download"></i>
                                    {{ __('Download') }}</a>
                                @endif
                              </div>
                            </div>
                            <div class="message">
                              <div class="summernote-content">
                                {!! $reply->reply !!}
                              </div>
                            </div>
                          </div>
                        @endif
                      @endforeach
                    @else
                      <p>{{ __('No Message Found') }}</p>
                    @endif
                  </div>
                  @if ($ticket->status == 2)
                    <hr>
                    <div class="reply-section">
                      <h5>{{ __('Reply') }}</h5>
                      <form action="{{ route('user.support_ticket.reply', $ticket->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                          <label for="">{{ __('Reply') . ' *' }} </label>
                          <textarea name="reply" class="form-control tinyMce"></textarea>
                          @error('reply')
                            <p class="text-danger">{{ $message }}</p>
                    @endif
                  </div>
                  <div class="form-group">
                    <input type="file" name="file" class="form-control" accept=".zip">
                    <p class="text-warning">{{ __('Max upload size is 20 MB') }} &amp;
                      {{ __('only .zip file is allowed.') }}</p>
                    @error('file')
                      <p class="text-danger">{{ $message }}</p>
                      @endif
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-md btn-primary icon-start">
                        <i class="fas fa-retweet"></i>
                        {{ __('Reply') }}</button>
                    </div>
                    </form>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
      </div>
      <!--====== End Dashboard Section ======-->
    @endsection
