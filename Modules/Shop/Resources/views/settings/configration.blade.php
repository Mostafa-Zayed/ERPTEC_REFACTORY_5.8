<div class="pos-tab-content">
    <div class="row">
    	<div class="form-group">
                        {!! Form::label('name', __('shop::lang.shop_name') . ' :') !!}
                        {!! Form::text('name',$settings['name']?? null,['class' => 'form-control','required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('url', __('shop::lang.shop_url') . ' :') !!}
                        {!! Form::text('url',$settings['url']?? null,['class' => 'form-control','required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('code', __('shop::lang.shop_code') . ' :') !!}
                        {!! Form::text('code',$settings['code'] ?? null,['class' => 'form-control','required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('username', __('shop::lang.username') . ' :') !!}
                        {!! Form::text('username',$settings['username'] ?? null,['class' => 'form-control','required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('password', __('shop::lang.password') . ' :') !!}
                        {!! Form::password('password',['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Type', __('shop::lang.type') . ' :') !!}
                        {!! Form::select('type',['admin' => 'Admin', 'seller' => 'Seller'],$settings['type'] ?? null,['class' => 'form-control','required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::submit('Add Settings',['class' => 'btn btn-primary']) !!}
                    </div>
    </div>
</div>