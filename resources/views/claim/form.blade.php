<simple-claim-form></simple-claim-form>

<script type="text/x-template" id="simple-claim-form-template">
    <div>
        <h2 class="mb-5">Иск по займу</h2>

        <user-type-form title="Истец" :data="data.plaintiff" vkey="plaintiff"></user-type-form>

        <user-type-form title="Должник" :data="data.respondent" vkey="respondent"></user-type-form>

        <div class="g-brd-around g-brd-gray-light-v4 g-pa-30 g-mb-30">

            <div class="form-group">
                <label class="custom-control custom-radio">
                    <input type="radio" v-model="data.basis_of_loan" value="voucher" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Расписка</span>
                </label>

                <label class="custom-control custom-radio">
                    <input type="radio" v-model="data.basis_of_loan" value="contract" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Договор</span>
                </label>
            </div>

            <div class="form-group form-row">
                <div class="col">
                    <label v-text="LabelDateOfSigning"></label>
                    <date-picker v-model="data.date_of_signing" :config="config" validation-key="date_of_signing"></date-picker>
                </div>
                <div class="col">
                    <label v-text="LabelDateOfBorrowing"></label>
                    <date-picker v-model="data.date_of_borrowing" :config="config" validation-key="date_of_borrowing"></date-picker>
                </div>
                <div class="col">
                    <label v-text="LabelDateOfReturn"></label>
                    <date-picker v-model="data.date_of_return" :config="config" validation-key="date_of_return"></date-picker>
                </div>
            </div>

            <div class="form-group">
                <label>Сумма займа</label>
                <input class="form-control" v-model.number="data.amount" type="number" validation-key="amount">

                <div v-text="amountHelpText" class="form-text text-muted"></div>
            </div>

            <hr>

            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" v-model="data.has_partly_returned_money">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Осуществлялся ли должником частичный возврат суммы займа?</span>
                </label>
            </div>

            <div v-if="data.has_partly_returned_money">
                <ul class="list-group mb-4" v-if="data.partly_returned_money.length">
                    <li class="list-group-item" v-for="(row, index) in data.partly_returned_money">
                        <button type="button" class="close" @click="removePartlyReturnedMoneyRow(index)">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <div class="form-group form-row">
                            <div class="col">
                                <label>Дата возврата</label>

                                <date-picker
                                        v-model="row.date"
                                        :config="config"
                                        :validation-key="'partly_returned_money.'+index+'.date'"></date-picker>
                            </div>
                            <div class="col">
                                <label>Сумма</label>
                                <input
                                        class="form-control"
                                       v-model.number="row.amount"
                                       type="number"
                                       :validation-key="'partly_returned_money.'+index+'.amount'">
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
                                <date-picker
                                        v-model="row.date"
                                        :config="config"
                                        :validation-key="'claimed_money.'+index+'.date'"></date-picker>
                            </div>
                            <div class="col">
                                <label>Сумма</label>
                                <input
                                        class="form-control"
                                        v-model.number="row.amount"
                                        type="number"
                                        validation-key="'claimed_money.'+index'.amount'">
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

            <hr>

            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" v-model="data.has_forfeit"  class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Договором предусмотрена неустойка за несвоевременный возврат суммы займа.</span>
                </label>
            </div>

            <div v-if="data.has_forfeit">
                {{--<div class="form-group">
                    <label class="custom-control custom-radio">
                        <input type="radio" v-model="data.forfeit.type" value="mulct" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Штраф</span>
                    </label>
                    <label class="custom-control custom-radio">
                        <input type="radio" v-model="data.forfeit.type" value="fine" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Пеня</span>
                    </label>
                </div>
                --}}

                <div class="form-group">
                    <div v-if="data.forfeit.type == 'mulct'">
                        <label>Размер штрафа</label>
                        <input
                                class="form-control"
                                v-model.number="data.forfeit.mulct"
                                type="number"
                                validation-key="forfeit.mulct" >
                    </div>

                    <div v-if="data.forfeit.type == 'fine'">
                        <percents-form form-key="forfeit_fine_interval" :data="data.forfeit.fine"></percents-form>
                    </div>
                </div>
            </div>
        </div>

        <div class="g-brd-around g-brd-gray-light-v4 g-pa-30 g-mb-30" v-if="link">
            <div class="row text-center text-uppercase">
                <div class="col-md-4">
                    <div class="js-counter g-font-size-35 g-font-weight-300 g-mb-7" v-text="tax"></div>
                    <h4 class="h5 g-color-gray-dark-v4">Гос пошлина</h4>
                </div>

                <div class="col-md-4">
                    <div class="js-counter g-font-size-35 g-font-weight-300 g-mb-7" v-text="percents.percents"></div>
                    <h4 class="h5 g-color-gray-dark-v4">Сумма процентов</h4>
                </div>

                <div class="col-md-4">
                    <div class="js-counter g-font-size-35 g-font-weight-300 g-mb-7" v-text="percents.amount_with_percents"></div>
                    <h4 class="h5 g-color-gray-dark-v4">Сумма возврата</h4>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-lg btn-primary" @click="send">
            Сформировать
        </button>

        <a :href="link" class="btn btn-lg btn-success" v-if="link">
            Скачать документ
        </a>
    </div>
</script>

<script type="text/x-template" id="user-type-form-template">
    <div class="g-brd-around g-brd-gray-light-v4 g-pa-30 g-mb-30">
        <div class="form-group">
            <h3 v-text="title"></h3>

            <label class="custom-control custom-radio" v-for="(key, type) in types">
                <input type="radio" v-model.number="data.type" :name="title" :value="type" class="custom-control-input">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description" v-text="key"></span>
            </label>
        </div>

        <div class="form-group">
            <label v-text="LabelName"></label>
            <input type="text" class="form-control" v-model="data.name" :validation-key="vkey+'.name'">
        </div>

        <div class="form-group">
            <label v-text="LabelAddress"></label>
            <input type="text" class="form-control" v-model="data.address" :validation-key="vkey+'.address'">
        </div>

        <div class="form-group">
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" v-model="data.has_fact_address">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">Имеется ли адрес для почтовой корреспонденции?</span>
            </label>
        </div>

        <div class="form-group" v-if="data.has_fact_address">
            <label v-text="LabelFactAddress"></label>
            <input type="text" class="form-control" v-model="data.fact_address" :validation-key="vkey+'.fact_address'">
        </div>

        <div class="form-group">
            <label v-text="LabelPhone"></label>
            <input type="text" class="form-control" v-model="data.phone" :validation-key="vkey+'.phone'">
        </div>
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

@push('scripts')
    <script type="text/javascript">
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

        Vue.component('user-type-form', {
            props: {
                vkey: {
                    required: true,
                    type: String
                },
                title: {
                    required: true,
                    type: String
                },
                data: {
                    required: true,
                    type: Object
                }
            },
            template: '#user-type-form-template',
            data() {
                return {
                    types: {
                        1: 'Гражданин',
                        2: 'Индивидуальный предприниматель',
                        3: 'Организация'
                    }
                }
            },
            computed: {
                LabelName() {
                    switch (this.data.type) {
                        case 1:
                            return 'ФИО';
                        case 2:
                            return 'Наименование';
                        case 3:
                            return 'Наименование';
                    }
                },
                LabelAddress() {
                    switch (this.data.type) {
                        case 1:
                            return 'Адрес прописки';
                        case 2:
                            return 'Адрес регистрации';
                        case 3:
                            return 'Адрес регистрации';
                    }
                },
                LabelFactAddress() {
                    switch (this.data.type) {
                        case 1:
                            return 'Адрес проживания';
                        case 2:
                            return 'Адрес нахождения';
                        case 3:
                            return 'Адрес нахождения';
                    }
                },
                LabelPhone() {
                    return 'Контактный телефон';
                }
            }
        });

        Vue.component('simple-claim-form', {
            template: '#simple-claim-form-template',
            mixins: [ValidationMixin],
            data() {
                return {
                    config: {
                        format: 'DD.MM.YYYY',
                        useCurrent: false
                    },
                    link: null,
                    tax: 0,
                    percents: {
                        amount: 0,
                        amount_with_percents: 0,
                        percents: 0,
                    },
                    data: {
                        plaintiff: {
                            type: 1,
                            name: '',
                            address: '',
                            fact_address: '',
                            has_fact_address: false,
                            phone: ''
                        },

                        respondent: {
                            type: 1,
                            name: '',
                            address: '',
                            fact_address: '',
                            has_fact_address: false,
                            phone: ''
                        },

                        basis_of_loan: 'voucher',
                        date_of_signing: '',
                        date_of_borrowing: '',
                        date_of_return: '',
                        amount: 0,

                        has_partly_returned_money: false,
                        partly_returned_money: [],

                        has_claimed_money: false,
                        claimed_money: [],

                        is_interest_bearing_loan: false,
                        interest_bearing_loan: {
                            percent: 0,
                            interval: 'monthly'
                        },
                        has_forfeit: false,
                        forfeit: {
                            type: 'fine',
                            mulct: 0,
                            fine: {
                                percent: 0,
                                interval: 'monthly'
                            }
                        }
                    }
                }
            },
            mounted() {
              let localHistory = s.get('form-history');

              if(_.isObject(localHistory)) {
                  //this.data = localHistory;
              }
            },
            watch: {
                data: {
                    handler(){
                        _.delay(data => s.set('form-history', data), 5000, this.data);
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
                    this.clearInvalidInputs();

                    this.link = null;

                    this.$api.claim.generateDocument(this.data).then(response => {

                        this.link = response.data.link;
                        this.tax = response.data.tax;
                        this.percents = response.data.percents;

                    });
                }
            },

            computed: {
                LabelDateOfSigning() {
                    switch (this.data.basis_of_loan) {
                        case 'voucher':
                            return 'Дата выдачи расписки';
                        case 'contract':
                            return 'Дата заключения договора';
                    }
                },
                LabelDateOfBorrowing() {
                    return 'Дата передачи денег';
                },
                LabelDateOfReturn() {
                    return 'Дата возврата денег';
                },

                amountHelpText() {
                    if (this.data.plaintiff.type == 1 || this.data.respondent.type == 1) {
                        if (this.data.amount > 500000) {
                            return 'Дело рассматривается в порядке искового производства (ст. 121 ГПК РФ)';
                        } else {
                            return 'Дело рассматривается в порядке приказного производства';
                        }
                    }

                    if (this.data.respondent.type == 3) {
                        if (this.data.amount > 500000) {
                            return 'Дело рассматривается в порядке в порядке искового производства (ст. 227 АПК РФ)';
                        } else {
                            return 'Дело рассматривается в порядке упрощённого производства';
                        }
                    }

                    if (this.data.respondent.type ==  2) {
                        if (this.data.amount > 250000) {
                            return 'Дело рассматривается в порядке в порядке искового производства (ст. 227 АПК РФ)';
                        } else {
                            return 'Дело рассматривается в порядке упрощённого производства';
                        }
                    }
                }
            }
        })
    </script>
@endpush