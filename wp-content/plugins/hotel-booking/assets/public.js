const { __, _x, _n, _nx } = wp.i18n;

Vue.prototype.message = {
    my_reserv: __('Ma réservation', 'hotel-booking'),
    area: __('Zone', 'hotel-booking'),
    capacity: __('Capacité', 'hotel-booking'),
    to_book: __('Réserver', 'hotel-booking'),
    guest: __('Invitée', 'hotel-booking'),
    breakfast: __('Petit-déjeuner', 'hotel-booking'),
    parking: __('Parking', 'hotel-booking'),
    arrival: __('Heure arrivée ', 'hotel - booking '),
    price: __('Prix', 'hotel-booking'),
    yes: __('Oui', 'hotel-booking'),
    no: __('Non', 'hotel-booking'),
    promocode: __('Promo code', 'hotel-booking'),
    arrival_date: __('Arrivée', 'hotel-booking'),
    departure_date: __('Départ', 'hotel-booking'),
    check_availability: __('Voir les disponibilités', 'hotel-booking'),
    time: __('Temps', 'hotel-booking'),
    meter: __('m', 'hotel-booking'),
    booking_form: __('Formulaire de reservation', 'hotel-booking'),
    fullname: __('Nom et prénom', 'hotel-booking'),
    tel: __('Phone', 'hotel-booking'),
    email: __('Email', 'hotel-booking'),
    noty: __('Vœux', 'hotel-booking'),
    send: __('Envoyer', 'hotel-booking'),
    back: __('Retour', 'hotel-booking'),
    left: __('Libre', 'hotel-booking'),
    qty_night: __('Nuits', 'hotel-booking'),
    add_services: __('Des services supplémentaires', 'hotel-booking'),
    order: __('Formulaire du commande', 'hotel-booking'),
    guests: __('Invitée', 'hotel-booking'),
    check_booking: __('Vérifier la réservation', 'hotel-booking'),
    order_id: __('commande id', 'hotel-booking'),
    check: __('Vérifier', 'hotel-booking'),
    order_success: __('Commande avec succès', 'hotel-booking'),
    return: __('Retouner', 'hotel-booking'),
    sorry: __('Cette date est déjà prise', 'hotel-booking'),
    per_night: __('par nuit', 'hotel-booking'),
};

Vue.prototype.datepicker_lang = {
    night: __('Nuit', 'hotel-booking'),
    nights: __('Nuits', 'hotel-booking'),
    'day-names-short': [
        __('Dim', 'hotel-booking'),
        __('Lun', 'hotel-booking'),
        __('Mar', 'hotel-booking'),
        __('Mer', 'hotel-booking'),
        __('Jeu', 'hotel-booking'),
        __('Ven', 'hotel-booking'),
        __('Sam', 'hotel-booking')
    ],
    'day-names': [
        __('Dimanche', 'hotel-booking'),
        __('Lundi', 'hotel-booking'),
        __('Mardi', 'hotel-booking'),
        __('Mercredi', 'hotel-booking'),
        __('Jeudi', 'hotel-booking'),
        __('Vendredi', 'hotel-booking'),
        __('Samedi', 'hotel-booking')
    ],
    'month-names-short': [
        __('Jan', 'hotel-booking'),
        __('Fev', 'hotel-booking'),
        __('Mar', 'hotel-booking'),
        __('Avr', 'hotel-booking'),
        __('Mai', 'hotel-booking'),
        __('Juin', 'hotel-booking'),
        __('Juil', 'hotel-booking'),
        __('Aout', 'hotel-booking'),
        __('Sep', 'hotel-booking'),
        __('Oct', 'hotel-booking'),
        __('Nov', 'hotel-booking'),
        __('Dec', 'hotel-booking')
    ],
    'month-names': [
        __('Janvier', 'hotel-booking'),
        __('Fevrier', 'hotel-booking'),
        __('Mars', 'hotel-booking'),
        __('Avril', 'hotel-booking'),
        __('Mai', 'hotel-booking'),
        __('Juin', 'hotel-booking'),
        __('Juillet', 'hotel-booking'),
        __('Aout', 'hotel-booking'),
        __('Septembre', 'hotel-booking'),
        __('Octobre', 'hotel-booking'),
        __('Novembre', 'hotel-booking'),
        __('Decembre', 'hotel-booking')
    ],
};

Vue.use(VueAwesomeSwiper);

Vue.component('modal0', {
    data() {
        return {
            fields: {},
            errors: {},
        }
    },
    methods: {
        submit() {
            axios.post(ajaxurl + '?action=wpb_check', this.fields).then(response => {
                document.getElementById('check_result').innerHTML = '<div class="mt-3">' + response.data + '</div>';
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
    },
    template: `
      <div>
      <div class="modal-mask">
<div class="modal-wrapper">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
<h5 class="modal-title">{{ message.check_booking }}</h5>
<button type="button" class="close" @click="$emit('close')" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
      </div>
      <div class="modal-body" id="checkBooking">
<form class="row" @submit.prevent="submit">
  <div class="col-4">
    <input type="text" v-model="fields.tel" class="form-control" :placeholder="message.tel" required/>
  </div>
  <div class="col-4">
    <input type="text" v-model="fields.order_id" class="form-control" :placeholder="message.order_id"
   required/>
  </div>
  <div class="col-4">
    <button class="btn btn-primary btn-block" type="submit">{{ message.check }}</button>
  </div>
</form>
<div id="check_result"></div>
      </div>
    </div>
  </div>
</div>
      </div>
      </div>`
});

Vue.component('modal1', {
    data() {
        return {
            errors: {},
            booking: {},
            selected_days: this.$root.$data.selected_days,
            selected_datestart: this.$root.$data.selected_datestart,
            selected_dateend: this.$root.$data.selected_dateend,
            selected_room_type: this.$root.$data.selected_room_type,
            selected_room_type_id: this.$root.$data.selected_room_type_id,
            selected_arrival: this.$root.$data.selected_arrival,
            selected_breakfast: this.$root.$data.selected_breakfast,
            selected_parking: this.$root.$data.selected_parking,
            add_services_list: this.$root.$data.add_services_list,
            currentCurrency: this.$root.$data.currentCurrency,
            currencies: this.$root.$data.currencies,
            selected_cost: this.$root.$data.selected_cost,
            selected_guest: this.$root.$data.selected_guest,
        }
    },
    methods: {
        submit() {

            let inputElements = document.getElementsByClassName('add_services');
            let a = 0;
            this.booking.add_services = {};
            for (let i = 0; inputElements[i]; i++) {
                if (inputElements[i].checked) {
                    this.booking.add_services[a] = inputElements[i].value;
                    a++;
                }
            }

            this.booking.days = this.selected_days;
            this.booking.datestart = this.selected_datestart;
            this.booking.dateend = this.selected_dateend;
            this.booking.arrival_date = this.selected_arrival_date;
            this.booking.departure_date = this.selected_departure_date;
            this.booking.promocode = this.selected_promocode;
            this.booking.room_type = this.selected_room_type;
            this.booking.room_type_id = this.selected_room_type_id;
            this.booking.arrival = this.selected_arrival;
            this.booking.breakfast = this.selected_breakfast;
            this.booking.parking = this.selected_parking;
            this.booking.currency = this.currentCurrency;
            this.booking.cost = this.selected_cost;
            this.booking.guest = this.selected_guest;

            this.errors = {};
            axios.post(ajaxurl + '?action=wpb_send', this.booking)
                .then(function(response) {
                    console.log(response.data);
                    window.document.getElementsByClassName('modal-content')[0].innerHTML = '<div class="text-center p-5"><h1 >' + Vue.prototype.message.order_success + '</h1> <a class="btn btn-success" style="text-decoration:none" href="">' + Vue.prototype.message.back + '</a><br/><br/></div>';
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getCurrencySign() {
            for (let i = 0; i < this.currencies.length; i++) {
                if (this.currencies[i][0] === this.currentCurrency) {
                    return this.currencies[i][1]
                }
            }
        },
    },
    template: `
      <div>
      <div class="modal-mask">
<div class="modal-wrapper">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <form class="modal-content" @submit.prevent="submit">
      <div class="modal-header">
<h5 class="modal-title">{{ message.order }} - {{ selected_room_type }}</h5>
<button type="button" class="close" @click="$emit('close')" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>
      </div>
      <div class="modal-body">
<div class="row">
  <div class="col-4">
    <ul class="list-group">
      <li class="list-group-item py-1">
{{ message.arrival_date }} <b class="float-right">{{ selected_datestart }}</b><br/>
<b class="float-right">{{ selected_arrival }}</b>
      </li>
      <li class="list-group-item py-1">
{{ message.departure_date }} <b class="float-right">{{ selected_dateend }}</b>
      </li>

      <li class="list-group-item py-1">
{{ message.qty_night }} <b class="float-right">{{ selected_days }}</b>
      </li>
      <li class="list-group-item py-1">
{{ message.price }} <b class="float-right">{{ selected_cost }} {{ getCurrencySign() }}</b>
      </li>

      <li class="list-group-item py-1">
{{ message.breakfast }} <b class="float-right">{{ message[selected_breakfast] }}</b>
      </li>
      <li class="list-group-item py-1">
{{ message.parking }} <b class="float-right">{{ message[selected_parking] }}</b>
      </li>
      <li class="list-group-item py-1">
{{ message.guests }} <b class="float-right">{{ selected_guest }}</b>
      </li>
    </ul>
  </div>
  <div class="col-8">
    <div class="mb-3 row">
      <label class="col-form-label col-3">{{ message.fullname }}</label>
      <div class="col-9">
<input type="text" v-model="booking.fullname" class="form-control"
       :placeholder="message.fullname" required/>
      </div>
    </div>
    <div class="mb-3 row">
      <label class="col-form-label col-3">{{ message.tel }}</label>
      <div class="col-6">
<input type="text" v-model="booking.tel" class="form-control" :placeholder="message.tel"
       required/>
      </div>
    </div>
    <div class="mb-3 row">
      <label class="col-form-label col-3">{{ message.email }}</label>
      <div class="col-6">
<input type="email" v-model="booking.email" class="form-control" placeholder="E-mail"/>
      </div>
    </div>
    <div class="mb-3 row">
      <label class="col-form-label col-3">{{ message.add_services }}</label>
      <div class="col-9">
<div v-for="list in add_services_list">
  <label class="d-inline">
    <input class="add_services" type="checkbox" :value="list"/>
    {{ list }}
  </label>
</div>
      </div>
    </div>
    <div class="mb-3 row">
      <label class="col-form-label col-3">{{ message.noty }}</label>
      <div class="col-9">
<textarea class="form-control" v-model="booking.noty" rows="3"
  :placeholder="message.noty"></textarea>
      </div>
    </div>
  </div>
</div>
      </div>
      <div class="modal-footer">
<button type="button" class="btn btn-warning" style="margin-right:15px; color:white;"
@click="$emit('close')">{{ message.back }}
</button>
<button class="btn btn-success" type="submit">{{ message.send }}</button>
      </div>
    </form>
  </div>
</div>
      </div>
      </div>`
});

if (document.getElementById("hotel-booking")) {

    const hotel_booking = new Vue({
        el: '#hotel-booking',
        data: {
            add_services_list: {},
            datepicker: {},
            selected_days: 0,
            selected_datestart: 0,
            selected_dateend: 0,
            selected_room_type: '',
            selected_room_type_id: '',
            selected_arrival: '',
            selected_breakfast: '',
            selected_parking: '',
            selected_cost: '',
            selected_guest: '',
            cost_item: 0,
            date_range: '',
            promocode: '',
            swiperOption: {
                pagination: {
                    el: '.swiper-pagination',
                    dynamicBullets: true
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                loop: true,
            },
            showModalCheckBooking: false,
            showModalBooking: false,
            currentCurrency: 'USD',
            currencies: [],
            rooms: [],
            showLoader: true,
        },
        created: function() {
            this.showLoader = false
        },
        mounted() {

            axios.get(ajaxurl + '?action=wpb_get').then(response => {
                this.rooms = response.data.rooms;
                this.currencies = response.data.currencies;
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });

            this.initDatePicker();

            let today = new Date();
            let tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            let input1 = document.querySelector('#input-id');
            input1.value = fecha.format(today, 'DD.MM.YYYY') + ' - ' + fecha.format(tomorrow, 'DD.MM.YYYY');
            this.date_range = input1.value;

            this.selected_datestart = fecha.format(today, 'DD.MM.YYYY');
            this.selected_dateend = fecha.format(tomorrow, 'DD.MM.YYYY');
            this.selected_days = datepicker.getNights();

            input1.addEventListener('afterClose', this.getNight, false);

        },
        methods: {
            toBook(room) {
                this.showModalBooking = true;
                this.selected_room_type = room.name;
                this.selected_room_type_id = room.id;
                this.selected_arrival = window.document.getElementById('time_' + room.id).value;
                this.selected_breakfast = window.document.getElementById('breakfast_' + room.id).value;
                this.selected_parking = window.document.getElementById('parking_' + room.id).value;
                this.add_services_list = room.add_services;
                this.selected_cost = window.document.getElementById('guest_' + room.id).value;
                this.selected_guest = window.document.getElementById('guest_' + room.id).options[window.document.getElementById('guest_' + room.id).selectedIndex].text;
            },
            getCurrencySign() {
                for (var i = 0; i < this.currencies.length; i++) {
                    if (this.currencies[i][0] === this.currentCurrency) {
                        return this.currencies[i][1]
                    }
                }
            },
            getCurrencyRatio() {
                for (var i = 0; i < this.currencies.length; i++) {
                    if (this.currencies[i][0] === this.currentCurrency) {
                        return this.currencies[i][2]
                    }
                }
            },
            search() {
                let data = {
                    'range': this.date_range,
                    'promocode': this.promocode,
                };

                this.showLoader = true

                axios.post(ajaxurl + '?action=wpb_get', data).then(response => {
                    this.rooms = response.data.rooms;
                    this.showLoader = false
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });

            },
            getNight() {
                this.selected_days = datepicker.getNights();
                let dateRange = document.querySelector('#input-id').value;
                this.date_range = dateRange;
                dateRange = dateRange.split(' - ');
                this.selected_datestart = dateRange[0];
                this.selected_dateend = dateRange[1];
            },
            initDatePicker() {
                datepicker = new HotelDatepicker(document.getElementById('input-id'), {
                    format: 'DD.MM.YYYY',
                    startOfWeek: 'monday',
                    showTopbar: false,
                    selectForward: true,
                    i18n: this.datepicker_lang,
                    onSelectRange: function() {
                        hotel_booking.search()
                    },
                });
                return datepicker;
            },
            showLightbox(imageName, index) {
                this.$refs.lightbox[index].show(imageName);
            },
            changeGuest: function(e, item) {
                this.$refs.cost[item].innerText = e.target.value;
            },
            changeCurrentCurrency: function() {
                let i = 0;
                while (i < this.rooms.length) {
                    this.$el.getElementsByClassName('guest')[i].selectedIndex = 0;
                    let selectedCost = parseInt(this.rooms[i].capacity_cost[0] * this.getCurrencyRatio());
                    this.$refs.cost[i].innerText = selectedCost;
                    i++;
                }
            },
        },
    });

}