<div class="row">
    <div class="col-md-12">
        <div class="base-wrapper">
            <h2>Карта для оплаты работы</h2>

            @if($payerCard = auth()->user()->payerCard())
                <div class="row">
                    <div class="col-md-6">
                        <img src="/images/wallet-one.png" alt="Wallet One" style="width: 50px">
                        <div>Единая касса</div>
                    </div>
                    <div class="col-md-6">
                        <form method="post" action="{{ route('escrow-payer-card-delete') }}" class="pull-right">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-trash" title="Удалить"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                @if(auth()->user()->phone)
                    <p>Вы еще не добавили карту.</p>
                    <form method="post" action="{{ $walletOnePayerAction }}">
                        @foreach($walletOnePayerField as $name => $value)
                            <input type="hidden" name="{{ $name }}" value="{{ $value }}"/>
                        @endforeach

                        <input type="submit" class="btn btn-primary" value="Добавить"/>
                    </form>
                @else
                    <p>Необходимо сначала добавить ваш номер телефона на вкладке <b>"Редактиовать профиль"</b>.</p>
                @endif
            @endif
        </div>
    </div>

    <div class="col-md-12">
        <div class="base-wrapper">
            <h2>Карта для получения платежей</h2>

            @if($beneficiaryCard = auth()->user()->beneficiaryCard())
                <div class="row">
                    <div class="col-md-6">
                        <img src="/images/wallet-one.png" alt="Wallet One" style="width: 50px">
                        <div>Единая касса</div>
                    </div>
                    <div class="col-md-6">
                        <form method="post" action="{{ route('escrow-beneficiary-card-delete') }}" class="pull-right">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-trash" title="Удалить"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                @if(auth()->user()->phone)
                    <p>Вы еще не добавили карту.</p>
                    <form method="post" action="{{ $walletOneBeneficiaryAction }}">
                        @foreach($walletOneBeneficiaryField as $name => $value)
                            <input type="hidden" name="{{ $name }}" value="{{ $value }}"/>
                        @endforeach

                        <input type="submit" class="btn btn-primary" value="Добавить"/>
                    </form>
                @else
                    <p>Необходимо сначала добавить ваш номер телефона на вкладке <b>"Редактиовать профиль"</b>.</p>
                @endif
            @endif
        </div>
    </div>
</div>