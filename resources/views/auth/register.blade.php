@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Register</div>
        <div class="panel-body">
          <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('account_id') ? ' has-error' : '' }}">
              <label for="account_id" class="col-md-4 control-label">ユーザーID</label>

              <div class="col-md-6">
                <input id="account_id" type="text" class="form-control" name="account_id" value="{{ old('account_id') }}" required autofocus>

                @if ($errors->has('account_id'))
                <span class="help-block">
                  <strong>{{ $errors->first('account_id') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label for="name" class="col-md-4 control-label">ユーザー名</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>

                @if ($errors->has('name'))
                <span class="help-block">
                  <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-4 control-label">メールアドレス</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
              <label for="company_id" class="col-md-4 control-label">企業</label>
              <div class="col-md-6">
                <select id="company_id" class="form-control" name="company_id" value="{{ old('company_id') }}" required>
                  @foreach ($companies as $company)
                  <option value="{{ $company->company_id }}">{{ $company->company_name }}</option>
                  @endforeach
                </select>

                @if ($errors->has('company_id'))
                <span class="help-block">
                  <strong>{{ $errors->first('company_id') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('department_id') ? ' has-error' : '' }}">
              <label for="department_id" class="col-md-4 control-label">所属課</label>
              <div class="col-md-6">
                <select id="department_id" class="form-control" name="department_id" value="{{ old('department_id') }}" required>
                  @foreach ($departments as $department)
                  <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                  @endforeach
                </select>

                @if ($errors->has('department_id'))
                <span class="help-block">
                  <strong>{{ $errors->first('department_id') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
              <label for="phone" class="col-md-4 control-label">内線</label>

              <div class="col-md-6">
                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>

                @if ($errors->has('phone'))
                <span class="help-block">
                  <strong>{{ $errors->first('phone') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password" class="col-md-4 control-label">パスワード</label>

              <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                <span class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label for="password-confirm" class="col-md-4 control-label">パスワード（確認用）</label>

              <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                  新規登録
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
