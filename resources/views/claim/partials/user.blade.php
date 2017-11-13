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

        <div class="form-group" v-if="!isPerson">
            <label v-text="LabelOGRN"></label>
            <masked-input class="form-control" mask="1111111111111" v-model="data.ogrn" :validation-key="vkey+'.ogrn'"></masked-input>
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

            <masked-input v-model="data.phone" class="form-control" mask="\+7 (111) 111-11-11" :validation-key="vkey+'.phone'"></masked-input>
        </div>

        <div class="form-group">
            <label v-text="LabelEmailAddress"></label>
            <input type="text" class="form-control" v-model="data.email" :validation-key="vkey+'.email'">
        </div>
    </div>
</script>

@push('scripts')

<script>
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
            isPerson() {
                return this.data.type == 1;
            },
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
            LabelOGRN() {
                switch (this.data.type) {
                    case 2:
                        return 'ОГРНИП';
                    case 3:
                        return 'ОГРН';
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
            },
            LabelEmailAddress() {
                return 'Электронная почта (Необязательно)';
            }
        }
    });
</script>
@endpush