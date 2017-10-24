<simple-claim-form></simple-claim-form>

<script type="text/x-template" id="simple-claim-form-template">
    <div>
        <user-type-form title="Истец" :data="data.plaintiff"></user-type-form>
        <user-type-form title="Должник" :data="data.respondent"></user-type-form>

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
                    <input type="text" class="form-control" v-model="data.date_of_signing">
                </div>
                <div class="col">
                    <label v-text="LabelDateOfBorrowing"></label>
                    <input type="text" class="form-control" v-model="data.date_of_borrowing">
                </div>
                <div class="col">
                    <label v-text="LabelDateOfReturn"></label>
                    <input type="text" class="form-control" v-model="data.date_of_return">
                </div>
            </div>

            <div class="form-group">
                <label>Сумма займа</label>
                <input class="form-control" v-model.number="data.amount" type="number">

                <div v-text="amountHelpText" class="form-text text-muted"></div>
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
                <ul class="list-group mb-4">
                    <li class="list-group-item" v-for="(row, index) in data.partly_returned_money">
                        <button type="button" class="close" @click="removePartlyReturnedMoneyRow(index)">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <div class="form-group form-row">
                            <div class="col">
                                <label>Дата возврата</label>
                                <input type="text" class="form-control" v-model="row.date">
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
                <div class="form-group">
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


                <div class="form-group">
                    <div v-if="data.forfeit.type == 'mulct'">
                        <label>Размер штрафа</label>
                        <input class="form-control" v-model.number="data.forfeit.mulct" type="number">
                    </div>

                    <div v-if="data.forfeit.type == 'fine'">
                        <percents-form form-key="forfeit_fine_interval" :data="data.forfeit.fine"></percents-form>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-lg btn-primary" @click="send">
            Сохранить
        </button>
    </div>
</script>

<script type="text/x-template" id="user-type-form-template">
    <div class="g-brd-around g-brd-gray-light-v4 g-pa-30 g-mb-30">
        <div class="form-group">
            <h3 v-text="title"></h3>

            <label class="custom-control custom-radio" v-for="(key, type) in types">
                <input type="radio" v-model="data.type" :name="title" :value="type" class="custom-control-input">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description" v-text="key"></span>
            </label>
        </div>

        <div class="form-group">
            <label v-text="LabelName"></label>
            <input type="text" class="form-control" v-model="data.name">
        </div>

        <div class="form-group">
            <label v-text="LabelAddress"></label>
            <input type="text" class="form-control" v-model="data.address">
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
            <input type="text" class="form-control" v-model="data.fact_address">
        </div>

        <div class="form-group">
            <label v-text="LabelPhone"></label>
            <input type="text" class="form-control" v-model="data.phone">
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
                        citizen: 'Гражданин',
                        ip: 'Индивидуальный предприниматель',
                        organization: 'Организация'
                    }
                }
            },
            computed: {
                // Истец
                LabelName() {
                    switch (this.data.type) {
                        case 'citizen':
                            return 'ФИО';
                        case 'ip':
                            return 'Наименование';
                        case 'organization':
                            return 'Наименование';
                    }
                },
                LabelAddress() {
                    switch (this.data.type) {
                        case 'citizen':
                            return 'Адрес прописки';
                        case 'ip':
                            return 'Адрес регистрации';
                        case 'organization':
                            return 'Адрес регистрации';
                    }
                },
                LabelFactAddress() {
                    switch (this.data.type) {
                        case 'citizen':
                            return 'Адрес проживания';
                        case 'ip':
                            return 'Адрес нахождения';
                        case 'organization':
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
            data() {
                return {
                    data: {
                        plaintiff: {
                            type: 'citizen',
                            name: '',
                            address: '',
                            fact_address: '',
                            has_fact_address: false,
                            phone: ''
                        },

                        respondent: {
                            type: 'citizen',
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
                        has_returned_money: false,
                        partly_returned_money: [],
                        is_interest_bearing_loan: false,
                        interest_bearing_loan: {
                            percent: 0,
                            interval: 'monthly'
                        },
                        has_forfeit: false,
                        forfeit: {
                            type: 'mulct',
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
                  this.data = localHistory;
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
                        date: '',
                        amount: 0
                    })

                    console.log(this.data.partly_returned_money)
                },
                removePartlyReturnedMoneyRow(i) {
                    this.data.partly_returned_money.splice(i, 1);
                },

                send() {
                    axios.post('/store-document', this.data).then(response => {
                        console.log(response)
                    })
                }
            },

            computed: {
                // Истец
                LabelPlaintiffName() {
                    switch (this.data.plaintiff.type) {
                        case 'citizen':
                            return 'ФИО';
                        case 'ip':
                            return 'Наименование';
                        case 'organization':
                            return 'Наименование';
                    }
                },
                LabelPlaintiffAddress() {
                    switch (this.data.plaintiff.type) {
                        case 'citizen':
                            return 'Адрес прописки';
                        case 'ip':
                            return 'Адрес регистрации';
                        case 'organization':
                            return 'Адрес регистрации';
                    }
                },
                LabelPlaintiffFactAddress() {
                    switch (this.data.plaintiff.type) {
                        case 'citizen':
                            return 'Адрес проживания';
                        case 'ip':
                            return 'Адрес нахождения';
                        case 'organization':
                            return 'Адрес нахождения';
                    }
                },
                LabelPlaintiffPhone() {
                    return 'Контактный телефон (заполняется по желанию)';
                },


                // Должник
                LabelRespondentName() {
                    switch (this.data.respondent.type) {
                        case 'citizen':
                            return 'ФИО';
                        case 'ip':
                            return 'Наименование';
                        case 'organization':
                            return 'Наименование';
                    }
                },
                LabelRespondentAddress() {
                    switch (this.data.respondent.type) {
                        case 'citizen':
                            return 'Адрес прописки';
                        case 'ip':
                            return 'Адрес регистрации';
                        case 'organization':
                            return 'Адрес регистрации';
                    }
                },

                LabelRespondentHasFactAddress() {
                    return 'Имеется ли адрес для почтовой корреспонденции?';
                },

                LabelRespondentFactAddress() {
                    switch (this.data.respondent.type) {
                        case 'citizen':
                            return 'Адрес проживания';
                        case 'ip':
                            return 'Адрес нахождения';
                        case 'organization':
                            return 'Адрес нахождения';
                    }
                },
                LabelRespondentPhone() {
                    return 'Контактный телефон (если имеется)';
                },

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
                    if (this.data.plaintiff.type == 'citizen' || this.data.respondent.type == 'citizen') {
                        if (this.data.amount > 500000) {
                            return 'Дело рассматривается в порядке искового производства (ст. 121 ГПК РФ)';
                        } else {
                            return 'Дело рассматривается в порядке приказного производства';
                        }
                    }

                    if (this.data.respondent.type == 'organization') {
                        if (this.data.amount > 500000) {
                            return 'Дело рассматривается в порядке в порядке искового производства (ст. 227 АПК РФ)';
                        } else {
                            return 'Дело рассматривается в порядке упрощённого производства';
                        }
                    }

                    if (this.data.respondent.type == 'ip') {
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