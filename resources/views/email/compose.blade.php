@extends('layouts.app')

@section('pagecss')
	<link rel="stylesheet" href="{{ asset('assets/css/dropzone/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/summernote/summernote.css') }}">
@endsection

@section('content')
<div class="inbox-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                @include('email.sidebar')
            </div>
            <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                <div class="view-mail-list sm-res-mg-t-30">
                    <div class="view-mail-hd">
                        <div class="view-mail-hrd">
                            <h2>New Message</h2>
                        </div>
                    </div>
                    <div class="cmp-int mg-t-20">
                        <div class="row">
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                <div class="cmp-int-lb cmp-int-lb1 text-right">
                                    <span>To: </span>
                                </div>
                            </div>
                            <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                <div class="form-group">
                                    <div class="nk-int-st cmp-int-in cmp-email-over">
                                        <input type="email" class="form-control" placeholder="example@email.com" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                <div class="cmp-int-lb cmp-int-lb1 text-right">
                                    <span>Cc: </span>
                                </div>
                            </div>
                            <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                <div class="form-group">
                                    <div class="nk-int-st cmp-int-in cmp-email-over">
                                        <input type="email" class="form-control" placeholder="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                                <div class="cmp-int-lb text-right">
                                    <span>Subject: </span>
                                </div>
                            </div>
                            <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                <div class="form-group cmp-em-mg">
                                    <div class="nk-int-st cmp-int-in cmp-email-over">
                                        <input type="text" class="form-control" placeholder="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cmp-int-box mg-t-20">
                        <div class="html-editor-cm">
                            <h2>Hello Mamunur Roshid!</h2>
                            <p>Dummy text of the printing and typesetting industry. Lorem Ipsum has been the <b>dustrys standard dummy text</b> ever since the 1500s, when an unknown printer took a galley of types and scrambleded it to make a type specimenen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages <a href="#">Read more</a>.</p>
                            <p>All the Lorem Ipsum generators on the Internet tend to repeat the predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence crisity structures, to generate Lorem Ipsum which looks reasonable. recently with.Dummy text of the printing and typesetting industryunknown printer took a galley of type.</p>
                            <span class="vw-tr">Thanks and Regards</span>
                            <span>Mark Smith</span>
                        </div>
                    </div>
                    <div class="multiupload-sys">
                        <div id="dropzone" class="dropmail">
                            <form action="/upload" class="dropzone dropzone-custom needsclick" id="demo-upload">
                                @csrf
                                <div class="dz-message needsclick download-custom">
                                    <i class="notika-icon notika-cloud" aria-hidden="true"></i>
                                    <h2>Drop files here or click to upload.</h2>
                                    <p><span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="vw-ml-action-ls text-right mg-t-20">
                        <div class="btn-group ib-btn-gp active-hook nk-email-inbox">
                            <button class="btn btn-default btn-sm waves-effect"><i class="notika-icon notika-next"></i> Reply</button>
                            <button class="btn btn-default btn-sm waves-effect"><i class="notika-icon notika-right-arrow"></i> Forward</button>
                            <button class="btn btn-default btn-sm waves-effect"><i class="notika-icon notika-down-arrow"></i> Print</button>
                            <button class="btn btn-default btn-sm waves-effect"><i class="notika-icon notika-trash"></i> Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
	<script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/summernote/summernote-updated.min.js') }}"></script>
    <script src="{{ asset('assets/js/summernote/summernote-active.js') }}"></script>
@endsection