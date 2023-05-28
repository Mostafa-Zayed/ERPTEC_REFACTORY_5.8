<!-- default value -->
@php
    $go_back_url = action('SellPosController@index');
    $transaction_sub_type = '';
    $view_suspended_sell_url = action('SellController@index').'?suspended=1';
    $pos_redirect_url = action('SellPosController@create');
@endphp

@if(!empty($pos_module_data))
    @foreach($pos_module_data as $key => $value)
        @php
            if(!empty($value['go_back_url'])) {
                $go_back_url = $value['go_back_url'];
            }

            if(!empty($value['transaction_sub_type'])) {
                $transaction_sub_type = $value['transaction_sub_type'];
                $view_suspended_sell_url .= '&transaction_sub_type='.$transaction_sub_type;
                $pos_redirect_url .= '?sub_type='.$transaction_sub_type;
            }
        @endphp
    @endforeach
@endif

<input type="hidden" name="transaction_sub_type" id="transaction_sub_type" value="{{$transaction_sub_type}}">
@inject('request', 'Illuminate\Http\Request')
<div class="col-md-12 no-print pos-header">
  <input type="hidden" id="pos_redirect_url" value="{{$pos_redirect_url}}">
  <div class="row">
    <div class="col-md-6">
      <div class="m-6 mt-5" style="display: flex;">
        <p ><strong>@lang('sale.location'): &nbsp;</strong> 
          @if(empty($transaction->location_id))
            @if(count($business_locations) > 1)
            <div style="width: 28%;margin-bottom: 5px;">
               {!! Form::select('select_location_id', $business_locations, $default_location->id ?? null , ['class' => 'form-control input-sm',
                'id' => 'select_location_id', 
                'required', 'autofocus'], $bl_attributes); !!}
            </div>
            @else
              {{$default_location->name}}
            @endif
          @endif

          @if(!empty($transaction->location_id)) {{$transaction->location->name}} @endif &nbsp;{{ @format_datetime('now') }} <i class="fa fa-keyboard hover-q text-muted" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="@include('sale_pos.partials.keyboard_shortcuts_details')" data-html="true" data-trigger="hover" data-original-title="" title=""></i>
        </p>
      </div>
    </div>
    <div class="col-md-6">
      <a href="{{$go_back_url}}" title="{{ __('lang_v1.go_back') }}" class="btn btn-info btn-flat m-6 btn-xs m-5 pull-right">
        <strong><i class="fa fa-backward fa-lg"></i></strong>
      </a>
      @can('close_cash_register')
      <button type="button" id="close_register" title="{{ __('cash_register.close_register') }}" class="btn btn-danger btn-flat m-6 btn-xs m-5 btn-modal pull-right" data-container=".close_register_modal" 
          data-href="{{ action('CashRegisterController@getCloseRegister')}}">
            <strong><i class="fa fa-window-close fa-lg"></i></strong>
      </button>
      @endcan
      
      @can('view_cash_register')
      <button type="button" id="register_details" title="{{ __('cash_register.register_details') }}" class="btn btn-success btn-flat m-6 btn-xs m-5 btn-modal pull-right" data-container=".register_details_modal" 
          data-href="{{ action('CashRegisterController@getRegisterDetails')}}">
            <strong><i class="fa fa-briefcase fa-lg" aria-hidden="true"></i></strong>
      </button>
      @endcan

      <button title="@lang('lang_v1.calculator')" id="btnCalculator" type="button" class="btn btn-success btn-flat pull-right m-5 btn-xs mt-10 popover-default" data-toggle="popover" data-trigger="click" data-content='@include("layouts.partials.calculator")' data-html="true" data-placement="bottom">
            <strong><i class="fa fa-calculator fa-lg" aria-hidden="true"></i></strong>
      </button>

      <button type="button" title="{{ __('lang_v1.full_screen') }}" class="btn btn-primary btn-flat m-6 hidden-xs btn-xs m-5 pull-right" id="full_screen">
            <strong><i class="fa fa-window-maximize fa-lg"></i></strong>
      </button>

      <button type="button" id="view_suspended_sales" title="{{ __('lang_v1.view_suspended_sales') }}" class="btn bg-yellow btn-flat m-6 btn-xs m-5 btn-modal pull-right" data-container=".view_modal" 
          data-href="{{$view_suspended_sell_url}}">
            <strong><i class="fa fa-pause-circle fa-lg"></i></strong>
      </button>
      @if(empty($pos_settings['hide_product_suggestion']) && isMobile())
        <button type="button" title="{{ __('lang_v1.view_products') }}"   
          data-placement="bottom" class="btn btn-success btn-flat m-6 btn-xs m-5 btn-modal pull-right" data-toggle="modal" data-target="#mobile_product_suggestion_modal">
            <strong><i class="fa fa-cubes fa-lg"></i></strong>
        </button>
      @endif

      @if(Module::has('Repair') && $transaction_sub_type != 'repair')
        @include('repair::layouts.partials.pos_header')
      @endif

        @if(in_array('pos_sale', $enabled_modules) && !empty($transaction_sub_type))
          @can('sell.create')
            <a href="{{action('SellPosController@create')}}" title="@lang('sale.pos_sale')" class="btn btn-success btn-flat m-6 btn-xs m-5 pull-right">
              <strong><i class="fa fa-th-large"></i> &nbsp; @lang('sale.pos_sale')</strong>
            </a>
          @endcan
        @endif

    </div>
    
  </div>
</div>


{{-- @inject('request', 'Illuminate\Http\Request')
<div class="no-print pos-header">
    <input type="hidden" id="pos_redirect_url" value="{{action('SellPosController@create')}}">
    <div class="row align-items-center">
        <div class="col-lg-6 col-md-5 mb-4 mb-md-0">
            <div class="d-flex align-items-center flex-wrap">
                <p class="position-relative mb-0 me-5"><strong>@lang('sale.location'):</strong> @if(!empty($default_location->name)) {{$default_location->name}} @elseif(!empty($transaction->location_id)) {{$transaction->location->name}} @endif <span id="timeClock"> {{ @format_datetime('now') }} </span> <i class="fas fa-keyboard hover-q text-muted" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="@include('sale_pos.partials.keyboard_shortcuts_details')" data-html="true" data-trigger="hover" data-original-title="" title=""></i></p>
                @yield("locaton_pos")                    
            </div>
        </div>
        <div class="col-lg-6 col-md-7 text-end"> 
            @if(Module::has('Repair'))
                @include('repair::layouts.partials.pos_header')
            @endif
            <button type="button" id="view_suspended_sales" title="{{ __('lang_v1.view_suspended_sales') }}" data-toggle="tooltip" data-placement="bottom" class="pos-header-btn btn6 btn-modal" data-container=".view_modal" 
                data-href="{{ action('SellController@index')}}?suspended=1">
                <i class="fas fa-pause-circle fa-lg"></i>
            </button>
            <button type="button" title="{{ __('lang_v1.full_screen') }}" data-toggle="tooltip" data-placement="bottom" class="pos-header-btn btn5" id="full_screen">
                <i class="fas fa-window-maximize fa-lg"></i>
            </button>
            <button title="@lang('lang_v1.calculator')" id="btnCalculator" type="button" class="pos-header-btn btn4 popover-default" data-toggle="popover" data-trigger="click" data-content='@include("layouts.partials.calculator")' data-html="true" data-placement="bottom">
                <i class="fas fa-calculator fa-lg" aria-hidden="true"></i>
            </button>
            <button type="button" id="register_details" title="{{ __('cash_register.register_details') }}" data-toggle="tooltip" data-placement="bottom" class="pos-header-btn btn3 btn-modal" data-container=".register_details_modal" 
                data-href="{{ action('CashRegisterController@getRegisterDetails')}}">
                <i class="fas fa-briefcase fa-lg" aria-hidden="true"></i>
            </button>
            <button type="button" id="close_register" title="{{ __('cash_register.close_register') }}" data-toggle="tooltip" data-placement="bottom" class="pos-header-btn btn2 btn-modal" data-container=".close_register_modal" 
                data-href="{{ action('CashRegisterController@getCloseRegister')}}">
                <i class="fas fa-window-close fa-lg"></i>
            </button>
            @if($request->segment(2) == 'retailer')
                <a href="{{ action('SellController@index_retailer')}}" title="{{ __('lang_v1.go_back') }}" data-toggle="tooltip" data-placement="bottom" class="pos-header-btn btn1">
                    <i class="fas fa-backward fa-lg"></i>
                </a>
            @else 
                <a href="{{ action('SellController@index')}}" title="{{ __('lang_v1.go_back') }}" data-toggle="tooltip" data-placement="bottom" class="pos-header-btn btn1">
                    <i class="fas fa-backward fa-lg"></i>
                </a>
            @endif 
        </div>
        <script>
            var myVar = setInterval(myTimer, 1000);
            function myTimer() {
                var d = new Date();
               // var t = d.toLocaleTimeString();
             //   var t = d.toISOString().split('T')[0];
             
                var hours = d.getHours();
              
            
              /*  if (hours > 12) {
                    hours -= 12;
                } else if (hours === 0) {
                    hours = 12;
                }
                */
               var t = [d.getMonth()+1,
                           d.getDate(),
                           d.getFullYear()].join('/')+' '+
                          [hours,
                           d.getMinutes(),
                           d.getSeconds()].join(':');
                $("#timeClock").html(t);
            }
        </script>
    </div>
</div>
--}}