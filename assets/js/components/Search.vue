<template>
  <div class="super-search-1337">
    <ais-instant-search
      :search-client="searchClient"
      :index-name="index"
    >
      <!--
        :highlightPreTag="'<strong>'"
        :highlightPostTag ="'</strong>'"
        :restrictSearchableAttributes="[
          'title', 'text'
        ]"
      -->
      <ais-configure
        :attributesToHighlight="[
          'title', 'content', 'contentShort'
        ]"
        :hitsPerPage="perPage"
      />


      <ais-search-box
        autofocus
        show-loading-indicator
      />

      <div class="d-flex align-items-center mt-3 mb-4">
        Sort by:

        <ais-sort-by
          :items="[
            { value: index, label: 'Featured' },
            { value: index + '_asc', label: 'Ascending' },
            { value: index + '_desc', label: 'Descending' },
            { value: index + '_sort_user', label: 'User' },
          ]"
          class="ml-2"
        />

        <b-btn
          v-b-toggle.collapseTags
          variant="secondary"
          class="ml-4"
        >
          Search by tags
        </b-btn>

        <b-btn
          v-b-toggle.collapseAuthors
          variant="secondary"
          class="ml-4"
        >
          Search by authors
        </b-btn>

        <b-btn
          variant="secondary"
          class="ml-4"
          @click="toggleItemsWide"
        >
          Switch size
        </b-btn>

        <div class="ml-4">
          <label
            for="perPageInput"
          >
            Per page:
          </label>

          <b-form-input
            v-model="perPage"
            id="perPageInput"
            type="text"
            style="width: 70px; display: inline;"
          />
        </div>
      </div>

      <b-collapse id="collapseTags">
        <b-card style="background: aliceblue;">
          <h5>Search by tags</h5>

          <ais-refinement-list
            attribute="tags.name"
            operator="and"
            searchable

          />
        </b-card>
      </b-collapse>

      <b-collapse
        id="collapseAuthors"
        class="mt-2"
      >
        <b-card style="background: antiquewhite;">
          <h5>Search by author</h5>

          <!--:show-more-limit="15"-->
          <!--show-more-->
          <ais-refinement-list
            attribute="author.username"
            operator="and"
            searchable
          />
        </b-card>
      </b-collapse>

      <ais-state-results>
        <template slot-scope="{ query, hits }">
          <div v-if="hits.length">
            <div style="position: relative;">
              <ais-stats style="position: absolute; left: 0;" />

              <ais-pagination class="my-3 mx-auto" />
            </div>

            <ais-hits class="mt-3">
              <div
                slot="item"
                slot-scope="{ item }"
              >
                <a :href="`/post/${item.id}/${item.slug}`">
                  <h2 v-html="item._highlightResult.title.value"></h2>
                </a>

                <div style="text-overflow: ellipsis; overflow: hidden; font-size: 0.9rem;">
                  <span style="white-space: nowrap;">
                    By
                    <b>{{ item.author.username }}</b>
                  </span>
                  at
                  <blog-date :date="item.createdAt" />

                  <div>
                    tags:

                    <b-badge
                      v-for="tag in item.tags"
                      :key="tag.name.id"
                      variant="secondary"
                      class="mr-1"
                    >
                      {{ tag.name }}
                    </b-badge>
                  </div>
                </div>

                <b-btn
                  v-if="item._highlightResult.content.matchedWords.length"
                  variant="primary"
                  class="mx-auto mt-3 d-block"
                  @click="modalShowTextHits = true; modalShowTextHitsData.text = item._highlightResult.content.value"
                >
                  Show text matches
                </b-btn>

                <!--item.text-->
                <div class="text-muted mt-3" v-html="decodeHtml(item._highlightResult.contentShort.value)"></div>
              </div>
            </ais-hits>

            <ais-pagination class="my-3" />
          </div>
          <div
            v-else
            class="text-center"
          >
            No results found for the query: <q>{{ query }}</q> 🙁.
          </div>
        </template>
      </ais-state-results>
    </ais-instant-search>
    <b-modal
      v-model="modalShowTextHits"
      size="lg"
      title="Text matches"
      cancel-disabled
      centered
    >
      <span v-html="decodeHtml(modalShowTextHitsData.text)"></span>
    </b-modal>
  </div>
</template>

<script>
import algoliasearch from 'algoliasearch/lite';
import 'instantsearch.css/themes/algolia-min.css';

export default {
  props: {
    index: {
      type: String,
      required: true
    }
  },
  data: () => ({
    perPage: 3,
    modalShowTextHits: false,
    modalShowTextHitsData: {
      text: null
    },
    itemsWide: false,
    searchClient: algoliasearch(
      'FYDKVZS0P8',
      '965e62979b3794dfefdc8c28ec3600df'
    )
  }),
  methods: {
    toggleItemsWide () {
      this.itemsWide = !this.itemsWide

      document.querySelectorAll('.ais-Hits-item')
        .forEach(i =>
          i.classList.toggle('ais-Hits-item--wide', this.itemsWide)
        )
    },
    decodeHtml (html) {
      const el = document.createElement('textarea');
      el.innerHTML = html;

      return el.value;
    }
  }
}
</script>
