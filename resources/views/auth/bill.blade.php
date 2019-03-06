<div class="row">
    <div class="col-md-12">
        <div class="base-wrapper">
            <h2>Карта для оплаты работы</h2>

            <p>Вы еще не добавили карту.</p>

            <form method="post" action="{{ $walletOnePayerAction }}">
                @foreach($walletOnePayerField as $name => $value)
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}"/>
                @endforeach

                <input type="submit" class="btn btn-primary" value="Добавить" />
            </form>
        </div>
    </div>
</div>