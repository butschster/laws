<simple-claim-form></simple-claim-form>

<script type="text/x-template" id="simple-claim-form-template">
    <div>
        <div class="g-brd-around g-brd-gray-light-v4 g-pa-30 g-mb-30">
            <div class="form-group">
                <h3>Истец</h3>

                <label class="custom-control custom-radio" v-for="(key, type) in types">
                    <input type="radio" v-model="data.plaintiff.type" name="plaintiff-type" :value="type" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description" v-text="key"></span>
                </label>
            </div>

            <div class="form-group">
                <label v-text="LabelPlaintiffName"></label>
                <input type="text" class="form-control" v-model="data.plaintiff.name">
            </div>

            <div class="form-group">
                <label v-text="LabelPlaintiffAddress"></label>
                <input type="text" class="form-control" v-model="data.plaintiff.address">
            </div>

            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" v-model="data.plaintiff.has_fact_address">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description">Имеется ли адрес для почтовой корреспонденции?</span>
                </label>
            </div>

            <div class="form-group" v-if="data.plaintiff.has_fact_address">
                <label v-text="LabelPlaintiffFactAddress"></label>
                <input type="text" class="form-control" v-model="data.plaintiff.fact_address">
            </div>

            <div class="form-group">
                <label v-text="LabelPlaintiffPhone"></label>
                <input type="text" class="form-control" v-model="data.plaintiff.phone">
            </div>
        </div>
        <div class="g-brd-around g-brd-gray-light-v4 g-pa-30 g-mb-30">
            <div class="form-group">
                <h3>Должник</h3>

                <label class="custom-control custom-radio" v-for="(key, type) in types">
                    <input type="radio" v-model="data.respondent.type" name="respondent-type" :value="type" class="custom-control-input">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description" v-text="key"></span>
                </label>
            </div>

            <div class="form-group">
                <label v-text="LabelRespondentName"></label>
                <input type="text" class="form-control" v-model="data.respondent.name">
            </div>

            <div class="form-group">
                <label v-text="LabelRespondentAddress"></label>
                <input type="text" class="form-control" v-model="data.respondent.address">
            </div>

            <div class="form-group">
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" v-model="data.respondent.has_fact_address">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description" v-text="LabelRespondentHasFactAddress"></span>
                </label>
            </div>

            <div class="form-group" v-if="data.respondent.has_fact_address">
                <label v-text="LabelRespondentFactAddress"></label>
                <input type="text" class="form-control" v-model="data.respondent.fact_address">
            </div>

            <div class="form-group">
                <label v-text="LabelRespondentPhone"></label>
                <input type="text" class="form-control" v-model="data.respondent.phone">
            </div>
        </div>
        <div class="g-brd-around g-brd-gray-light-v4 g-pa-30 g-mb-30">
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
                <input type="text" class="form-control" v-model.number="data.amount" type="number">

                <div v-text="amountHelpText" class="form-text text-muted"></div>
            </div>

            <div class="form-group form-row" v-for="(row, index) in data.partly_returned_money">
                <div class="col">
                    <label>Дата возврата</label>
                    <input type="text" class="form-control" v-model="row.date">
                </div>
                <div class="col">
                    <label>Сумма</label>
                    <input type="text" class="form-control" v-model.number="row.amount" type="number">
                </div>
            </div>
            <button type="button" class="btn btn-default" @click="addPartlyReturnedMoneyRow">
                Сообщить о частичном возврате
            </button>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</script>

@push('scripts')
    <script type="text/javascript">
        Vue.component('simple-claim-form', {
            template: '#simple-claim-form-template',
            data() {
                return {
                    types: {
                        citizen: 'Гражданин',
                        ip: 'Индивидуальный предприниматель',
                        organization: 'Организация'
                    },
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
                        partly_returned_money: []
                    }
                }
            },
            methods: {
                addPartlyReturnedMoneyRow() {
                    this.data.partly_returned_money.push({
                        date: '',
                        amount: 0
                    })

                    console.log(this.data.partly_returned_money)
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