<script type="text/x-template" id="percents-form-template">
    <div class="form-inline">
        <div class="form-group">
            <label class="mr-3">Процентная ставка</label>

            <div class="input-group mr-3">
                <input class="form-control text-right" v-model.number="data.percent" type="number" min="0" max="100">
                <div class="input-group-addon justify-content-center">%</div>
            </div>
        </div>

        <div class="form-group">
            <label class="custom-control custom-radio">
                <input type="radio" v-model="data.interval" name="key" value="daily" class="custom-control-input">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">Ежедневно</span>
            </label>
            <label class="custom-control custom-radio">
                <input type="radio" v-model="data.interval" name="key" value="weekly" class="custom-control-input">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">Еженедельно</span>
            </label>
            <label class="custom-control custom-radio">
                <input type="radio" v-model="data.interval" name="key" value="monthly" class="custom-control-input">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">Ежемесячно</span>
            </label>
            <label class="custom-control custom-radio">
                <input type="radio" v-model="data.interval" name="key" value="yearly" class="custom-control-input">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">Ежегодно</span>
            </label>
        </div>
    </div>
</script>

@push('scripts')
    <script>
        Vue.component('percents-form', {
            props: {
                'form-key': {
                    required: true,
                    type: String
                },
                data: {
                    required: true,
                    type: Object
                }
            },
            template: '#percents-form-template',
        });
    </script>
@endpush