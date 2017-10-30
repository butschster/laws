<calculator-form></calculator-form>

<script type="text/x-template" id="calculator-form-template">
    <div>
        <h2 class="mb-3">Калькулятор для расчета процентов по займу</h2>

        <div class="g-brd-around g-brd-gray-light-v4 g-pa-30 g-mb-30">
            <div class="form-group form-row">
                <div class="col">
                    <date-picker v-model="data.date_of_borrowing" :config="config"></date-picker>
                </div>
                <div class="col">
                    <date-picker v-model="data.date_of_return" :config="config"></date-picker>
                </div>
            </div>

            <div class="form-group">
                <label>Сумма займа</label>
                <input class="form-control" v-model.number="data.amount" type="number">
            </div>

            <hr>

            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" v-model="data.has_returned_money">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Осуществлялся ли должником частичный возврат суммы займа?</span>
                </label>
            </div>

            <div v-if="data.has_returned_money">
                <ul class="list-group mb-4" v-if="data.partly_returned_money.length">
                    <li class="list-group-item" v-for="(row, index) in data.partly_returned_money">
                        <button type="button" class="close" @click="removePartlyReturnedMoneyRow(index)">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <div class="form-group form-row">
                            <div class="col">
                                <label>Дата возврата</label>

                                <date-picker v-model="row.date" :config="config"></date-picker>
                            </div>
                            <div class="col">
                                <label>Сумма</label>
                                <input class="form-control" v-model.number="row.amount" type="number">
                            </div>
                        </div>
                    </li>
                </ul>

                <button type="button" class="btn btn-success" @click="addPartlyReturnedMoneyRow">
                    Добавить
                </button>
            </div>

            <hr>

            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" v-model="data.has_claimed_money">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Осуществлялось ли должником учеличение займа?</span>
                </label>
            </div>

            <div v-if="data.has_claimed_money">
                <ul class="list-group mb-4" v-if="data.claimed_money.length">
                    <li class="list-group-item" v-for="(row, index) in data.claimed_money">
                        <button type="button" class="close" @click="removeClaimedMoneyRow(index)">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <div class="form-group form-row">
                            <div class="col">
                                <label>Дата займа</label>
                                <date-picker v-model="row.date" :config="config"></date-picker>
                            </div>
                            <div class="col">
                                <label>Сумма</label>
                                <input class="form-control" v-model.number="row.amount" type="number">
                            </div>
                        </div>
                    </li>
                </ul>

                <button type="button" class="btn btn-success" @click="addClaimedMoneyRow">
                    Добавить
                </button>
            </div>

            <hr>

            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" v-model="data.is_interest_bearing_loan" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Займ является процентным </span>
                </label>
            </div>

            <div v-if="data.is_interest_bearing_loan">
                <percents-form form-key="basis_of_loan_interval" :data="data.interest_bearing_loan"></percents-form>
            </div>
        </div>

        <div class="card border-light mb-4" v-if="totalAmount > 0">
            <div class="card-header">Расчеты</div>
            <table class="table" v-if="summary.length">
                <thead>
                <tr>
                    <th scope="col">Дата</th>
                    <th scope="col" width="200px" class="text-right">Сумма</th>
                    <th scope="col" width="100px" class="text-right">Проценты</th>
                </tr>
                </thead>
                <tbody>
                    <table-summary v-for="row in summary" :data="row"></table-summary>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th class="text-right">Всего по процентам: </th>
                        <th class="text-right"><span v-text="totalPercentsAmount"></span></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th class="text-right">Итого: </th>
                        <th class="text-right"><span v-text="totalAmount"></span></th>
                    </tr>
                </tfoot>
            </table>
        </div>


        <button type="submit" class="btn btn-lg btn-primary" @click="send">
            Расчитать
        </button>
    </div>
</script>
<script type="text/x-template" id="percents-form-template">
    <div>
        <div class="form-group">
            <label>Процентная ставка</label>
            <input class="form-control" v-model.number="data.percent" type="number">
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

<script type="text/x-template" id="table-summary-template">
    <tr>
        <th v-text="date"></th>
        <td v-text="amount" class="text-right"></td>
        <td v-text="percents" class="text-right"></td>
    </tr>
</script>

@push('scripts')
    <script type="text/javascript">

        Vue.component('table-summary', {
            props: {
                data: {
                    required: true,
                    type: Object
                }
            },
            template: '#table-summary-template',
            computed: {
                date() {
                    return moment(this.data.start_date.date).format('DD.MM.YYYY');
                },
                amount() {
                    return this.data.amount.toMoney();
                },
                percents() {
                    return this.data.calculated_percents.toMoney();
                }
            }
        });

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

        Vue.component('calculator-form', {
            template: '#calculator-form-template',
            data() {
                return {
                    config: {
                        format: 'DD.MM.YYYY',
                        useCurrent: false,
                    },
                    data: {
                        date_of_borrowing: moment().subtract(1, 'year').format('DD.MM.YYYY'),
                        date_of_return: moment().format('DD.MM.YYYY'),
                        amount: 50000,

                        has_returned_money: false,
                        partly_returned_money: [],

                        has_claimed_money: false,
                        claimed_money: [],

                        is_interest_bearing_loan: true,
                        interest_bearing_loan: {
                            percent: 15,
                            interval: 'monthly'
                        }
                    },

                    totalPercentsAmount: 0,
                    totalAmount: 0,
                    summary: []
                }
            },
            mounted() {
                let localHistory = s.get('calculator-form-history');

                if(_.isObject(localHistory)) {
                    this.data = localHistory;
                }
            },
            watch: {
                data: {
                    handler() {
                        _.delay(data => s.set('calculator-form-history', data), 5000, this.data);
                    },
                    deep: true
                }
            },
            methods: {
                addPartlyReturnedMoneyRow() {
                    this.data.partly_returned_money.push({
                        date: moment().format('DD.MM.YYYY'),
                        amount: 0
                    })
                },
                removePartlyReturnedMoneyRow(i) {
                    this.data.partly_returned_money.splice(i, 1);
                },

                addClaimedMoneyRow() {
                    this.data.claimed_money.push({
                        date: moment().format('DD.MM.YYYY'),
                        amount: 0
                    })
                },
                removeClaimedMoneyRow(i) {
                    this.data.claimed_money.splice(i, 1);
                },

                send() {
                    axios.post('/claim-calculator', this.data).then(response => {

                        this.totalAmount = response.data.data.amount_with_percents;
                        this.totalPercentsAmount = response.data.data.percents;
                        this.summary = response.data.data.summary;

                    })
                }
            },

            computed: {
                LabelDateOfBorrowing() {
                    return 'Дата передачи денег';
                },

                LabelDateOfReturn() {
                    return 'Дата возврата денег';
                }
            }
        })
    </script>
@endpush