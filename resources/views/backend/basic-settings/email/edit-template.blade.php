@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Email Settings') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Basic Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Email Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Mail Templates') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Mail Template') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Mail Template') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.basic_settings.mail_templates') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body pt-5">
          <div class="row">
            <div class="col-lg-7">
              <form id="mailTemplateForm" action="{{ route('admin.basic_settings.update_mail_template', ['id' => $templateInfo->id]) }}" method="post">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      @php $mailType = str_replace('_', ' ', $templateInfo->mail_type); @endphp

                      <label>{{ __('Mail Type') }}</label>
                      <input type="text" class="form-control text-capitalize" name="mail_type" value="{{ $mailType }}" readonly>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label>{{ __('Mail Subject*') }}</label>
                      <input type="text" class="form-control" name="mail_subject" placeholder="Enter Mail Subject" value="{{ $templateInfo->mail_subject }}">
                      @if ($errors->has('mail_subject'))
                        <p class="mt-1 mb-0 text-danger">{{ $errors->first('mail_subject') }}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label>{{ __('Mail Body*') }}</label>
                      <textarea class="form-control summernote" name="mail_body" placeholder="Enter Mail Body Format" data-height="300">{{ replaceBaseUrl($templateInfo->mail_body, 'summernote') }}</textarea>
                      @if ($errors->has('mail_body'))
                        <p class="text-danger">{{ $errors->first('mail_body') }}</p>
                      @endif
                    </div>
                  </div>
                </div>
              </form>
            </div>

            @includeIf('backend.basic-settings.email.bbcode')
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="mailTemplateForm" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
