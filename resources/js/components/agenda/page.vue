<template lang="pug">
    .agenda-page.d-flex
        .filters
            v-date-picker(v-model="date", mode="single", is-inline, is-expanded)
            scrollable.overflow-hidden.list-group-wrapper
                .list-group.list-group-flush
                    li.list-group-item.list-group-item-action.py-2.px-3.d-flex.align-items-center.cursor-pointer(
                        v-for="n of 50", :key="n")
                        avatar(username="U S", :size="35")
                        span.ml-3 Item {{ n }}
        .content
            .d-flex.align-items-center
                .border(v-for="n of 10", :key="n", style="min-width: 300px") Lorem, ipsum dolor sit amet consectetur adipisicing elit. Facere quod ullam laudantium quo asperiores. In sapiente adipisci temporibus suscipit quis voluptate. Quae eum nostrum voluptates, architecto quidem quisquam illum eveniet!
</template>

<style lang="sass" scoped>
@import '~styles/variables'

.agenda-page
    position: absolute
    bottom: 0
    top: 0
    left: 0 
    right: 0

.filters
    width: 280px
    min-width: 280px

    .list-group-wrapper
        max-height: calc(100% - 274px)

.content

/deep/ 
    .ps__scrollbar-y-rail
        z-index: 2
    .c-day-background
        background-color: $primary !important

</style>


<script>
export default {
    data() {
        return {
            date: new Date(),
        };
    },
};
</script>
