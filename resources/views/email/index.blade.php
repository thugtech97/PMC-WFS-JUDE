@extends('layouts.app')

@section('content')
<div class="inbox-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                @include('email.sidebar')
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="inbox-text-list sm-res-mg-t-30">
                    <div class="form-group">
                        <div class="nk-int-st search-input search-overt">
                            <input type="text" class="form-control" placeholder="Search email..." />
                            <button class="btn search-ib">Search</button>
                        </div>
                    </div>
                    <div class="inbox-btn-st-ls btn-toolbar">
                        <div class="btn-group ib-btn-gp active-hook nk-email-inbox">
                            <button class="btn btn-default btn-sm"><i class="notika-icon notika-refresh"></i> Refresh</button>
                            <button class="btn btn-default btn-sm"><i class="notika-icon notika-next"></i></button>
                            <button class="btn btn-default btn-sm"><i class="notika-icon notika-down-arrow"></i></button>
                            <button class="btn btn-default btn-sm"><i class="notika-icon notika-trash"></i></button>
                            <button class="btn btn-default btn-sm"><i class="notika-icon notika-checked"></i></button>
                            <button class="btn btn-default btn-sm"><i class="notika-icon notika-promos"></i></button>
                        </div>
                        <div class="btn-group ib-btn-gp active-hook nk-act nk-email-inbox">
                            <button class="btn btn-default btn-sm"><i class="notika-icon notika-left-arrow"></i></button>
                            <button class="btn btn-default btn-sm"><i class="notika-icon notika-right-arrow"></i></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-inbox">
                            <tbody>
                                <tr class="unread">
                                    <td class="">
                                        <label><input type="checkbox" checked="" class="i-checks"></label>
                                    </td>
                                    <td><a href="{{ route('email.show') }}">Jeremy Massey</a></td>
                                    <td><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a>
                                    </td>
                                    <td><i class="notika-icon notika-paperclip"></i></td>
                                    <td class="text-right mail-date">Tue, Nov 25</td>
                                </tr>
                                <tr class="active">
                                    <td class="">
                                        <label><input type="checkbox" class="i-checks"></label>
                                    </td>
                                    <td><a href="{{ route('email.show') }}">Marshall Horne</a></td>
                                    <td><a href="#">Praesent nec nisl sed neque ornare maximus at ac enim.</a>
                                    </td>
                                    <td></td>
                                    <td class="text-right mail-date">Wed, Jan 13</td>
                                </tr>
                                <tr>
                                    <td class="">
                                        <label><input type="checkbox" class="i-checks"></label>
                                    </td>
                                    <td><a href="{{ route('email.show') }}">Grant Franco</a> <span class="label label-warning">Finance</span></td>
                                    <td><a href="#">Etiam maximus tellus a turpis tempor mollis.</a></td>
                                    <td></td>
                                    <td class="text-right mail-date">Mon, Oct 19</td>
                                </tr>
                                <tr class="unread active">
                                    <td class="">
                                        <label><input type="checkbox" class="i-checks"></label>
                                    </td>
                                    <td><a href="{{ route('email.show') }}">Ferdinand Meadows</a></td>
                                    <td><a href="#">Aenean hendrerit ligula eget augue gravida semper.</a></td>
                                    <td><i class="notika-icon notika-paperclip"></i></td>
                                    <td class="text-right mail-date">Sat, Aug 29</td>
                                </tr>
                                <tr>
                                    <td class="">
                                        <label><input type="checkbox" checked="" class="i-checks"></label>
                                    </td>
                                    <td><a href="{{ route('email.show') }}">Ivor Rios</a> <span class="label label-info">Social</span>
                                    </td>
                                    <td><a href="#">Sed quis augue in nunc venenatis finibus.</a></td>
                                    <td><i class="notika-icon notika-paperclip"></i></td>
                                    <td class="text-right mail-date">Sat, Dec 12</td>
                                </tr>
                                <tr>
                                    <td class="">
                                        <label><input type="checkbox" class="i-checks"></label>
                                    </td>
                                    <td><a href="{{ route('email.show') }}">Maxwell Murphy</a></td>
                                    <td><a href="#">Quisque eu tortor quis justo viverra cursus.</a></td>
                                    <td></td>
                                    <td class="text-right mail-date">Sun, May 17</td>
                                </tr>
                                <tr>
                                    <td class="">
                                        <label><input type="checkbox" class="i-checks"></label>
                                    </td>
                                    <td><a href="{{ route('email.show') }}">Henry Patterson</a></td>
                                    <td><a href="#">Aliquam nec justo interdum, ornare mi non, elementum lacus.</a></td>
                                    <td><i class="notika-icon notika-paperclip"></i></td>
                                    <td class="text-right mail-date">Thu, Aug 06</td>
                                </tr>
								<tr>
                                    <td class="">
                                        <label><input type="checkbox" class="i-checks"></label>
                                    </td>
                                    <td><a href="{{ route('email.show') }}">Maxwell Murphy</a></td>
                                    <td><a href="#">Quisque eu tortor quis justo viverra cursus.</a></td>
                                    <td></td>
                                    <td class="text-right mail-date">Sun, May 17</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="pagination-inbox">
                            <ul class="wizard-nav-ac">
                                <li><a class="btn" href="#"><i class="notika-icon notika-back"></i></a></li>
                                <li class="active"><a class="btn" href="#">1</a></li>
                                <li><a class="btn" href="#">2</a></li>
                                <li><a class="btn" href="#">3</a></li>
                                <li><a class="btn" href="#"><i class="notika-icon notika-next-pro"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection