<template>
  <div>
    <editor
      v-model="content"
    ></editor>

    <p>
      <!--TODO валидация на бекенде не считающая теги-->
      Content length (with tags): {{ content.length }}.
    </p>

    <b-alert
      :show="!!error"
      variant="danger"
      dismissible
      fade
    >
      {{ error }}
    </b-alert>

    <b-alert
      :show="!!successMessage"
      variant="success"
      dismissible
      fade
    >
      {{ successMessage }}
    </b-alert>

    <b-button
      class="mt-2"
      variant="primary"
      @click="onSubmit"
    >
      {{ loading ? 'Loading...' : 'Send'  }}
    </b-button>
  </div>
</template>

<script>
export default {
  props: ['apiEndpoint', 'parentCommentId'],
  data: () => ({
    successMessage: null,
    error: null,
    loading: false,
    content: `
<h4>JS</h4>
<pre><code>alert(1 + '1' === '11') // true</code></pre>
<h4>CSS</h4>
<pre><code>body { color: blue; }</code></pre>
<h4>PHP</h4>
<pre><code>class Test {
  public function sayHello() {
    echo 'Hello world!';
  }
}</code></pre>

<blockquote>
  Always code as if the guy who ends up maintaining your code will be a violent psychopath who knows where you live.
</blockquote>

<br>
    `
  }),
  methods: {
    async onSubmit () {
      this.successMessage = this.error = null
      this.loading = true

      try {
        const response = await fetch(this.apiEndpoint, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            message: this.content,
            parentCommentId: this.parentCommentId || null
          })
        })
        const res = await response.json()

        if (res.error) {
          this.error = res.error
        } else {
          this.successMessage = res.successMessage
        }
      } catch (e) {}

      this.loading = false
    }
  }
}
</script>
