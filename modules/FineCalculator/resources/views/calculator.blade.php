<fine-calculator-form></fine-calculator-form>

<script type="text/x-template" id="fine-calculator-form-template">
    <div>
        <h2 class="mb-3">Калькулятор для расчета процентов по займу</h2>

        <div class="g-brd-around g-brd-gray-light-v4 g-pa-30 g-mb-30">
            <div class="form-group">
                <select class="form-control" v-model="data.federal_district">
                    <option :value="index" v-for="(name, index) in districts" v-text="name"></option>
                </select>
            </div>

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
        </div>


        <button type="submit" class="btn btn-lg btn-primary" @click="send">
            Расчитать
        </button>

        <div class="card bg-light border-dark mt-4" v-if="totalAmount > 0">
            <div class="card-header">Расчеты</div>
            <table class="table" v-if="summary.length">
                <thead>
                <tr>
                    <th scope="col">Дата</th>
                    <th scope="col" width="200px" class="text-right">Сумма</th>
                    <th scope="col" width="100px" class="text-right">Ставка</th>
                    <th scope="col" width="100px" class="text-right">Проценты</th>
                </tr>
                </thead>
                <tbody>
                <fine-table-summary v-for="row in summary" :data="row"></fine-table-summary>
                </tbody>
                <tfoot>
                <tr>
                    <th></th>
                    <th class="text-right" colspan="2">Всего по процентам:</th>
                    <th class="text-right"><span v-text="totalPercentsAmount"></span></th>
                </tr>
                <tr>
                    <th></th>
                    <th class="text-right" colspan="2">Итого:</th>
                    <th class="text-right"><span v-text="totalAmount"></span></th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</script>

<script type="text/x-template" id="fine-table-summary-template">
    <tr>
        <th><span v-text="date"></span> (<span v-text="days"></span>)</th>
        <td v-text="amount" class="text-right"></td>
        <td v-text="rate" class="text-right"></td>
        <td v-text="percents" class="text-right"></td>
    </tr>
</script>

@push('scripts')
    <script type="text/javascript">

        Vue.component('fine-table-summary', {
            props: {
                data: {
                    required: true,
                    type: Object
                }
            },
            template: '#fine-table-summary-template',
            computed: {
                date() {
                    return moment(this.data.from).format('DD.MM.YYYY') + ' - ' + moment(this.data.to).format('DD.MM.YYYY')
                },
                days() {
                    return this.data.days;
                },
                amount() {
                    return this.data.amount.toMoney();
                },
                percents() {
                    return this.data.percents.toMoney();
                },
                rate() {
                    return this.data.rate + '%';
                }
            }
        });

        Vue.component('fine-calculator-form', {
            template: '#fine-calculator-form-template',
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
                        federal_district: 1,

                        has_returned_money: false,
                        partly_returned_money: [],

                        has_claimed_money: false,
                        claimed_money: []
                    },

                    totalPercentsAmount: 0,
                    totalAmount: 0,
                    summary: [],
                    districts: {
                        1: 'Центральный федеральный округ',
                        2: 'Северо-Западный федеральный округ',
                        3: 'Южный федеральный округ',
                        4: 'Северо–Кавказский федеральный округ',
                        5: 'Приволжский федеральный округ',
                        6: 'Уральский федеральный округ',
                        7: 'Сибирский федеральный округ',
                        8: 'Дальневосточный федеральный округ',
                        9: 'Крымский федеральный округ'
                    }
                }
            },
            mounted() {
                let localHistory = s.get('fine-calculator-form-history');

                if (_.isObject(localHistory)) {
                    //this.data = localHistory;
                }
            },
            watch: {
                data: {
                    handler() {
                        _.delay(data => s.set('fine-calculator-form-history', data), 5000, this.data);
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
                    this.$api.claim.calculate395(this.data).then(response => {

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