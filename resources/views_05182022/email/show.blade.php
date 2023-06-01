@extends('layouts.app')

@section('content')
<div class="inbox-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                @include('email.sidebar')
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="view-mail-list sm-res-mg-t-30">
                    <div class="view-mail-hd">
                        <div class="view-mail-hrd">
                            <h2>Email view</h2>
                        </div>
                        <div class="view-ml-rl">
                            <p>08:26 PM (2 hours ago)</p>
                        </div>
                    </div>
                    <div class="mail-ads mail-vw-ph">
                        <p class="first-ph"><b>Subject: </b>Lorem Ipsum has been the industry's standard dummy text ever</p>
                        <p><b>Email:</b> <a href="#">example.@email.com</a></p>
                        <p class="last-ph"><b>Date:</b> 15.03.2018</p>
                    </div>
                    <div class="view-mail-atn">
                        <h2>Hello Mamunur Roshid!</h2>
                        <p>Dummy text of the printing and typesetting industry. Lorem Ipsum has been the <b>dustrys standard dummy text</b> ever since the 1500s, when an unknown printer took a galley of types and scrambleded it to make a type specimenen book. It hasn survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. when an unknown printer took a galley of types and scrambleded it to make a type specimenen book. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages <a href="#">Read more</a>.</p>
                        <p>All the Lorem Ipsum generators on the Internet tend to repeat the predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence crisity structures, to generate Lorem Ipsum which looks reasonable. recently with.Dummy text of the printing and typesetting industryunknown printer took a galley of type. when an unknown printer took a galley of types and scrambleded it's stambanner to make a type specimenen book.survived not only five centuries, but also the leap into the electronic typesetting, remaining essentially unchanged.</p>
                        <span class="vw-tr">Thanks and Regards</span>
                        <span>Mark Smith</span>
                    </div>
                    <div class="file-download-system">
                        <div class="dw-st-ic mg-t-20">
                            <div class="dw-atc-sn">
                                <span><i class="notika-icon notika-paperclip"></i> 4 attachments <i class="notika-icon notika-arrow-right atc-sign"></i></span>
                            </div>
                            <div class="dw-atc-sn">
                                <a class="btn dw-al-ft" href="#">Download all in zip format <i class="notika-icon notika-file"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="vw-ml-action-ls text-right mg-t-20">
                        <div class="btn-group ib-btn-gp active-hook nk-email-inbox">
                            <button class="btn btn-default btn-sm waves-effect"><i class="notika-icon notika-next"></i> Reply</button>
                            <button class="btn btn-default btn-sm waves-effect"><i class="notika-icon notika-right-arrow"></i> Forward</button>
                            <button class="btn btn-default btn-sm waves-effect"><i class="notika-icon notika-print"></i> Print</button>
                            <button class="btn btn-default btn-sm waves-effect"><i class="notika-icon notika-trash"></i> Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection