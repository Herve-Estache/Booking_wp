<div id="hotel-booking">

    <div class="row mb-3">
        <div class="col-3">
            <select class="custom-select" v-model="currentCurrency"  @change="changeCurrentCurrency">
                <option v-for="currency in currencies" :value="currency[0]">
                    {{currency[1]}} {{currency[0]}}
                </option>
            </select>
        </div>
        <div class="col-4">
            <button type="button" class="btn btn-block btn-secondary" @click="showModalCheckBooking=true">
                {{ message.my_reserv }}
            </button>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-5">
            <input id="input-id" v-model="date_range" type="text" class="form-control text-center" :placeholder="message.arrival_date+' - '+message.departure_date" required>
        </div>
        <div class="col-3">
            <input type="text" v-model="promocode" class="form-control" :placeholder="message.promocode">
        </div>
        <div class="col-4">
            <button type="button" @click="search" class="btn btn-block btn-primary">
                {{ message.check_availability }}
            </button>
        </div>
    </div>

    <div v-for="(room, item) in rooms" class="" style="border:1px solid #ced4da; margin-bottom:15px; padding:15px; background:#fff;">
        <div class="row">
            <div class="col-8">
                <div class="row">
                    <div class="col-5">
                        <template>
                            <swiper :options="swiperOption">
                                <swiper-slide v-for="(image, index) in room.images" :key="index">
                                    <img class="card-img" :src="image.name" @click="showLightbox(image, item)" />
                                </swiper-slide>
                                <div class="swiper-pagination swiper-pagination-white" slot="pagination"></div>
                                <div class="swiper-button-prev swiper-button-white" slot="button-prev"></div>
                                <div class="swiper-button-next swiper-button-white" slot="button-next"></div>
                            </swiper>
                            <lightbox
                                ref="lightbox"
                                :images="room.images"
                            />
                        </template>
                    </div>
                    <div class="col-7">
                        <h5 class="">{{ room.name }}</h5>
                        <div>
                            <b>{{ message.area }}:</b>
                            <span>{{ room.area }} {{ message.meter }}<sup>2</sup></span>
                        </div>
                        <div>
                            <b>{{ message.capacity }}:</b>
                            <span> {{ room.capacity }}</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <p class="mt-3" v-html="room.desc">/p>
                        <ul class="list-inline text-muted" style="font-size:12px">
                            <li v-for="comfort_item in room.comfort_list" class="list-inline-item">
                                {{ comfort_item }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="row">
                    <label class="col-5 col-form-label text-right">
                        {{ message.guest }}
                    </label>
                    <div class="col-7">
                        <select class="custom-select guest mb-2" ref="guest" @change="changeGuest($event, item)" :id="'guest_'+room.id">
                            <option v-for="(guest, opt) in room.capacity_guest" :value="parseInt(room.capacity_cost[opt]*getCurrencyRatio())">
                                {{ guest }}
                            </option>
                        </select>
                    </div>
                    <label class="col-5 col-form-label text-right">
                        {{ message.breakfast }}
                    </label>
                    <div class="col-7">
                        <select class="custom-select mb-2" :id="'breakfast_'+room.id">
                            <option value="yes">{{ message.yes }}</option>
                            <option value="no">{{ message.no }}</option>
                        </select>
                    </div>
                    <label class="col-5 col-form-label text-right">
                        {{ message.parking }}
                    </label>
                    <div class="col-7">
                        <select class="custom-select mb-2" :id="'parking_'+room.id">
                            <option value="yes">{{ message.yes }}</option>
                            <option value="no">{{ message.no }}</option>
                        </select>
                    </div>
                    <label class="col-5 col-form-label text-right">
                        {{ message.arrival }}
                    </label>
                    <div class="col-7">
                        <input type="time" :id="'time_'+room.id" :key="room.selected_arrival"  class="form-control mb-2" :placeholder="message.time" value="12:00">
                    </div>
                </div>
            </div>
            <div class="col-3 text-right col-form-label">
                {{ message.left }} <span class="badge badge-warning text-white">{{ room.available }}</span>
            </div>
            <div class="col-2 text-right col-form-label">
                {{ message.price }}
            </div>
            <div class="col-4">
                {{ getCurrencySign() }}
                <h5 class="d-inline-block" ref="cost" :id="'cost_'+room.id">
                    {{ parseInt(room.capacity_cost[0]*getCurrencyRatio()) }}
                </h5> {{ message.per_night }}
            </div>
            <div class="col-3 pr-3">
                <span v-if="room.available != 0">
                <button
                    class="btn btn-success btn-block"
                    type="button"
                    @click="toBook(room)"
                >{{ message.to_book }}</button>
                </span>
                <span v-if="room.available == 0">
                    <b class="text-danger">
                        {{ message.sorry }}
                    </b>
                </span>
            </div>
        </div>
    </div><!-- end rooms -->

    <modal0 v-if="showModalCheckBooking" @close="showModalCheckBooking=false"></modal0>
    <modal1 v-if="showModalBooking" @close="showModalBooking=false"></modal1>

    <div id="loader" v-if="showLoader" tabindex="0" aria-label="Loading" class="vld-overlay is-active is-full-page" style="" aria-busy="true">
        <div class="vld-background"></div>
        <div class="vld-icon">
            <svg viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="#000" width="60" height="240">
                <circle cx="15" cy="15" r="10.5062">
                    <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate>
                    <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate>
                </circle>
                <circle cx="60" cy="15" r="13.4938" fill-opacity="0.3">
                    <animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"></animate>
                    <animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate>
                </circle>
                <circle cx="105" cy="15" r="10.5062">
                    <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate>
                    <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate>
                </circle>
            </svg>
        </div>
    </div>

</div><!-- end app -->


