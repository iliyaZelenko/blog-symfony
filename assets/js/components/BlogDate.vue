<template>
  <small>
    🕒
    <time
      :datetime="iso"
      pubdate
      class=".text-muted"
    >
      {{ output }}
    </time>
  </small>
</template>

<script>
export default {
  props: ['date', 'time'],
  data: () => ({}),
  computed: {
    unix: {
      cached: false,
      get () {
        return new Date(+this.date * 1000)
      }
    },
    output () {
      return this.unix.toLocaleString()
    },
    iso () {
      return this.unix.toISOString()
    }
  }
}
</script>
