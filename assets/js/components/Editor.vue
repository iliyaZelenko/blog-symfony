<template>
  <div class="editor">
    <editor-menu-bar :editor="editor">
      <div
        class="menubar"
        slot-scope="{ commands, isActive }"
      >
        <b-button-group>
          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.bold() }"
            @click="commands.bold"
          >
            <icon name="bold" />
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.italic() }"
            @click="commands.italic"
          >
            <icon name="italic" />
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.strike() }"
            @click="commands.strike"
          >
            <icon name="strike" />
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.underline() }"
            @click="commands.underline"
          >
            <icon name="underline" />
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.code() }"
            @click="commands.code"
          >
            <icon name="code" />
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.paragraph() }"
            @click="commands.paragraph"
          >
            <icon name="paragraph" />
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.heading({ level: 1 }) }"
            @click="commands.heading({ level: 1 })"
          >
            H1
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.heading({ level: 2 }) }"
            @click="commands.heading({ level: 2 })"
          >
            H2
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.heading({ level: 3 }) }"
            @click="commands.heading({ level: 3 })"
          >
            H3
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.bullet_list() }"
            @click="commands.bullet_list"
          >
            <icon name="ul" />
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.ordered_list() }"
            @click="commands.ordered_list"
          >
            <icon name="ol" />
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.blockquote() }"
            @click="commands.blockquote"
          >
            <icon name="quote" />
          </b-button>

          <b-button
            class="menubar__button"
            :class="{ 'is-active': isActive.code_block() }"
            @click="commands.code_block"
          >
            <icon name="code" />
          </b-button>

          <b-button
            class="menubar__button"
            @click="commands.undo"
          >
            <icon name="undo" />
          </b-button>

          <b-button
            class="menubar__button"
            @click="commands.redo"
          >
            <icon name="redo" />
          </b-button>
        </b-button-group>
      </div>
    </editor-menu-bar>

    <b-card no-body>
      <b-card-body class="px-0 py-0">
        <editor-content class="editor__content" :editor="editor" />
      </b-card-body>
    </b-card>
  </div>
</template>

<script>
import { Editor, EditorContent, EditorMenuBar } from 'tiptap'
import {
  Blockquote,
  CodeBlock,
  HardBreak,
  Heading,
  OrderedList,
  BulletList,
  ListItem,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
  Link,
  Strike,
  Underline,
  History,
  Placeholder,
  CodeBlockHighlight
} from 'tiptap-extensions'
import javascript from 'highlight.js/lib/languages/javascript'
import php from 'highlight.js/lib/languages/php'
import css from 'highlight.js/lib/languages/css'
import Icon from './Icon'

export default {
  props: ['value'],
  components: {
    EditorContent,
    EditorMenuBar,
    Icon
  },
  data() {
    const val = this.value

    return {
      editor: new Editor({
        onUpdate: ({ getHTML }) => {
          this.$emit('input', getHTML())
        },
        extensions: [
          new Blockquote(),
          new BulletList(),
          new CodeBlock(),
          new HardBreak(),
          new Heading({ levels: [1, 2, 3] }),
          new ListItem(),
          new OrderedList(),
          new TodoItem(),
          new TodoList(),
          new Bold(),
          new Code(),
          new Italic(),
          new Link(),
          new Strike(),
          new Underline(),
          new History(),
          new Placeholder({
            emptyClass: 'is-empty',
            emptyNodeText: 'Write something …'
          }),
          new CodeBlockHighlight({
            languages: {
              javascript,
              css,
              php
            }
          })
        ],
        content: val
      })
    }
  },
  beforeDestroy() {
    this.editor.destroy()
  }
}
</script>
