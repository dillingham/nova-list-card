<template>
  <div :id="card.id" :class="'nova-list-card' + card.classes + ' p-8 bg-white relative'">
    <div
      class="nova-list-card-heading flex border-b pb-2 mb-2 border-50"
      v-if="card.heading.length != 0"
    >
      <div class="truncate left" :class="{'w-3/4': card.heading.right}">{{ card.heading.left }}</div>
      <div class="w-1/4 truncate right" v-if="card.heading.right">{{ card.heading.right }}</div>
    </div>
    <div class="nova-list-card-body relative">
      <div
        v-if="!loading && items.length == 0"
        class="text-center text-base text-80 font-normal mb-6 pt-8"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="65"
          height="51"
          viewBox="0 0 65 51"
          class="mb-3"
        >
          <g id="Page-1" fill="none" fill-rule="evenodd">
            <g
              id="05-blank-state"
              fill="#A8B9C5"
              fill-rule="nonzero"
              transform="translate(-779 -695)"
            >
              <path
                id="Combined-Shape"
                d="M835 735h2c.552285 0 1 .447715 1 1s-.447715 1-1 1h-2v2c0 .552285-.447715 1-1 1s-1-.447715-1-1v-2h-2c-.552285 0-1-.447715-1-1s.447715-1 1-1h2v-2c0-.552285.447715-1 1-1s1 .447715 1 1v2zm-5.364125-8H817v8h7.049375c.350333-3.528515 2.534789-6.517471 5.5865-8zm-5.5865 10H785c-3.313708 0-6-2.686292-6-6v-30c0-3.313708 2.686292-6 6-6h44c3.313708 0 6 2.686292 6 6v25.049375c5.053323.501725 9 4.765277 9 9.950625 0 5.522847-4.477153 10-10 10-5.185348 0-9.4489-3.946677-9.950625-9zM799 725h16v-8h-16v8zm0 2v8h16v-8h-16zm34-2v-8h-16v8h16zm-52 0h16v-8h-16v8zm0 2v4c0 2.209139 1.790861 4 4 4h12v-8h-16zm18-12h16v-8h-16v8zm34 0v-8h-16v8h16zm-52 0h16v-8h-16v8zm52-10v-4c0-2.209139-1.790861-4-4-4h-44c-2.209139 0-4 1.790861-4 4v4h52zm1 39c4.418278 0 8-3.581722 8-8s-3.581722-8-8-8-8 3.581722-8 8 3.581722 8 8 8z"
              ></path>
            </g>
          </g>
        </svg>
        <p>{{ __('No Results') }}</p>
      </div>
      <div v-if="loading" class="flex justify-center items-center absolute pin z-50 bg-white">
        <loader class="text-60"/>
      </div>
      <div v-if="!loading && items.length != 0">
        <router-link
          :to="{ name: 'detail', params: { resourceName: item.resourceName, resourceId: item.resourceId}}"
          v-for="(item, index) in items"
          :key="item.id"
          class="nova-list-card-item cursor-pointer block text-black no-underline"
          :class="'nova-list-card-item-'+(index + 1)"
        >
          <div class="flex py-1">
            <div
              :class="{'w-full': card.value_column == null, 'w-3/4 pr-4': card.value_column != null}"
            >
              <p
                class="nova-list-card-title truncate no-underline dim text-primary font-bold"
              >{{ item.title }}</p>
              <p class="nova-list-card-subtitle text-80 truncate pr-4" v-if="card.subtitle_enabled">
                <span
                  class="pb-2"
                  v-if="card.subtitle_column"
                >{{ item.resource[card.subtitle_column] }}</span>
                <span class="pb-2" v-else-if="item.subTitle">{{ item.subTitle }}</span>
              </p>
              <p
                class="text-80 nova-list-card-timestamp"
                v-if="card.timestamp_enabled"
              >{{ timestampValue(item.resource[card.timestamp_column], card.timestamp_format)}}</p>
              <p
                class="text-80"
                v-if="item.aggregate && card.relationship + '_' + card.aggregate != card.value_column && card.relationship + '_' + card.aggregate != card.subtitle_column"
              >{{ item.aggregate }} {{ card.relationship }}</p>
            </div>
            <div
              v-if="card.value_column != null"
              class="nova-list-card-value w-1/4 truncate"
            >{{ formatValue(item, card.value_format) }}</div>
          </div>
        </router-link>
        <div
          v-if="card.view_all_enabled && items.length == card.limit"
          class="nova-list-card-view-all border-t border-50 mt-4"
        >
          <router-link
            :to="{ name: card.view_all_route, params: viewAllParams}"
            class="cursor-pointer text-80 no-underline py-3 font-bold block dim"
            v-if="card.view_all_enabled"
          >{{ __('View All') }}</router-link>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.nova-list-card {
  height: auto;
  min-height: 150px;
}
.nova-list-card-body {
  min-height: 100px;
}
.nova-list-card.zebra .nova-list-card-item:nth-child(even) {
  background-color: var(--20);
}
</style>
<script>
import numerial from "numeral";

export default {
  props: ["card"],
  data() {
    return {
      items: [],
      loading: true
    };
  },
  mounted() {
    axios.get(this.endpoint).then(data => {
      this.items = data.data;
      this.loading = false;
    });
  },
  methods: {
    formatValue(item, format) {
      if (this.card.value_format == null) {
        return this.value(item, format);
      }
      if (this.card.value_formatter == "numerial") {
        return this.numerialValue(item, format);
      }
      if (this.card.value_formatter == "timestamp") {
        return this.timestampValue(
          item.resource[this.card.value_column],
          format
        );
      }
    },
    timestampValue(value, format) {
      let timestamp = moment(value);

      if (format != "relative") {
        return timestamp.format(format);
      } else {
        return timestamp.fromNow();
      }
    },
    numerialValue(item) {
      return numerial(item.resource[this.card.value_column]).format(
        this.card.value_format
      );
    },
    value(item) {
      return item.resource[this.card.value_column];
    }
  },
  computed: {
    viewAllParams() {
      if (this.card.view_all_route == "lens") {
        return {
          resourceName: this.card.uri_key,
          lens: this.card.view_all_key
        };
      } else {
        return { resourceName: this.card.uri_key };
      }
    },
    endpoint() {
      let endpoint =
        "/nova-vendor/nova-list-card/resources/" + this.card.uri_key + "/";

      if (this.card.relationship) {
        endpoint += this.card.aggregate + "/" + this.card.relationship + "/";
      }

      if (this.card.aggregate_column) {
        endpoint += this.card.aggregate_column + "/";
      }

      return (
        (endpoint +=
          "?order_by=" +
          this.card.order_column +
          "&direction=" +
          this.card.order_direction +
          "&limit=" +
          this.card.limit) +
        "&nova-list-card=" +
        this.card.id
      );
    }
  }
};
</script>