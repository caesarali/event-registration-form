@extends('layouts.participant')

@php
    $registration = $user->registration;
    $receipt = $registration->receipt;
@endphp

@section('content')
<div class="content-header">
    <div class="container">
        <h1 class="display-4">
            Hi, <a href="{{ route('profile.index') }}">{{ explode(" ", Auth::user()->name)[0] }}!</a>
        </h1>

        <p class="lead mb-1 font-weight-light">
            {{ site('description', config('app.desc')) }}.
        </p>

        @if (eventInfo('about'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                {!! linkify(eventInfo('about')) !!}

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</div>
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header border-bottom-0 font-weight-bold">
                        <i class="far fa-money-bill-alt mr-1"></i> Bill
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table mb-2">
                            <tbody>
                                <tr>
                                    <td nowrap>Kode Registrasi</td>
                                    <td width="1%">:</td>
                                    <td>{{ $registration->code }}</td>
                                </tr>
                                <tr>
                                    <td nowrap>Tanggal Registrasi</td>
                                    <td width="1%">:</td>
                                    <td>{{ $registration->created_at->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td nowrap>Paket Worksho</td>
                                    <td width="1%">:</td>
                                    <td>
                                        {{ IDR($registration->paybill) }} - "{{ $registration->package->name ?? $registration->package->name }}"
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap>Status</td>
                                    <td width="1%">:</td>
                                    <td>
                                        {!! $registration->status() !!}
                                        <a href="#" class="text-decoration-none text-muted" data-toggle="modal" data-target="#paymentInformation">
                                            <i class="far fa-question-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap>Pembayaran</td>
                                    <td width="1%">:</td>
                                    <td nowrap>
                                        @if (isset($receipt))
                                            @if ($receipt->file_info['extension'] == 'pdf')
                                                <a href="{{ $receipt->file_url }}" class="text-decoration-none text-muted" target="_blank">
                                                    <i class="far fa-file-pdf mr-1"></i> Bukti pembayaran
                                                </a>
                                            @else
                                                <a href="#" class="text-decoration-none text-muted" v-on:click.prevent="showPhoto('{{ $receipt->file_url }}')">
                                                    <i class="far fa-image mr-1"></i> Bukti pembayaran
                                                </a>
                                            @endif

                                            @if ($registration->status <= 2)
                                                | <a href="#" class="ml-1 text-decoration-none text-muted" data-toggle="modal" data-target="#paymentConfirmation">
                                                    <i class="far fa-edit"></i> edit
                                                </a>
                                            @endif
                                        @else
                                            <button class="btn btn-sm btn-primary px-4" data-toggle="modal" data-target="#paymentConfirmation">
                                                <i class="fas fa-upload mr-1"></i> Upload Bukti Pembayaran
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @if ($registration->status > 2)
                                    <tr>
                                        <td nowrap>Invoice</td>
                                        <td width="1%">:</td>
                                        <td nowrap>
                                            <a href="{{ url('/invoice') }}" class="text-decoration-none text-muted">
                                                <i class="fas fa-print mr-1"></i>
                                                Cetak Invoice
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        <i class="far fa-calendar-check mr-1"></i> Event / Workshop
                    </div>
                    <div class="card-body">
                        <dl>
                            @foreach ($registration->events as $item)
                                <li>{{ $item->name }}</li>
                            @endforeach
                        </dl>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header font-weight-bold">
                        <i class="fas fa-paperclip mr-1"></i> Attachment
                    </div>
                    <div class="card-body">
                        @foreach ($files as $file)
                            <div class="{{ !$loop->last ? 'border-bottom pb-2 mb-2' : '' }}">
                                <b>{{ $file->name }}</b>
                                <p class="text-secondary text-sm mb-2">
                                    {{ $file->description }}
                                </p>

                                <a href="{{ $file->download() }}" target="_blank" class="text-decoration-none text-secondary mr-2">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <a href="{{ $file->download() }}" download class="text-decoration-none text-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        @endforeach

                        @if (! $files->count())
                            <p class="text-muted mb-0">
                                Empty...
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="modal-area">
    <div class="modal fade" id="paymentInformation" role="dialog" aria-labelledby="paymentInformationLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="paymentInformationLabel">Metode Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <h5><u>Bank Mandiri</u> :</h5>
                        <p>
                            dr. Erlin Sjahril, Sp.Rad(K) <br>
                            152-00-5223240-8
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentConfirmation" role="dialog" aria-labelledby="paymentConfirmationLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentConfirmationLabel">Upload Bukti Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('confirm') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">A/N Rekening</label>
                            <input type="text" name="name" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name', $receipt->name ?? '') }}" placeholder="A/N Rekening" required>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="bank">Bank</label>
                            <input type="text" name="bank" id="bank" class="form-control{{ $errors->has('bank') ? ' is-invalid' : '' }}" value="{{ old('bank', $receipt->bank ?? '') }}" placeholder="Nama Bank" required>
                            @if ($errors->has('bank'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('bank') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="paid_at">Tanggal Transfer</label>
                            <input type="date" name="paid_at" id="paid_at" class="form-control{{ $errors->has('paid_at') ? ' is-invalid' : '' }}" value="{{ old('paid_at', $receipt ? $receipt->getOriginal('paid_at') : '') }}" placeholder="Tanggal Transfer sesuai dengan struk." required>
                            @if ($errors->has('paid_at'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('paid_at') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="struk">Bukti Transfer <small class="text-muted">(JPG/PDF, maks: 2048 KB)</small></label>
                            <input id="struk" type="file" class="{{ $errors->has('struk') ? 'form-control is-invalid' : '' }}" accept=".png,.jpeg,.jpg,.pdf" name="struk" value="{{ old('struk') }}" placeholder="Foto Bukti Pembayaran" {{ !$receipt ? 'required' : '' }}>
                            @if ($errors->has('struk'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('struk') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if ($registration->status < 3)
                            <button type="submit" class="btn btn-primary btn-block-xs"><i class="fas {{ $receipt ? 'fa-check' : 'fa-upload' }} mr-1"></i> {{ $receipt ? 'Update' : 'Upload' }}</button>
                        @else
                            <button type="button" class="btn btn-secondary btn-block-xs" data-dismiss="modal">Close</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<light-box ref="lightbox"></light-box>
@endsection

@push('scripts')
    @if ($errors->any())
        <script>
            $('#paymentConfirmation').modal('show')
        </script>
    @endif
    <script>
        new Vue({
            el: '#app',
            methods: {
                showPhoto(src) {
                    let image = {
                        src: src
                    }
                    this.$refs.lightbox.open(image)
                }
            }
        })
    </script>
@endpush