<template>
  <template>
    <template v-if="input.type === 'label'">
      <small style="font-weight: bold">{{ input.label }}</small>
    </template>
    <template v-if="input.type === 'text'">
      <v-text-field
        outlined
        v-model="input.value"
        :label="getInputLabel(input)"
        :rules="input.required ? [requiredRule] : []"
        :required="input.required"
        :error-messages="errorMessage(input)"
        dense
      ></v-text-field>
    </template>
    <template v-else-if="input.type === 'textarea'">
      <v-textarea
        outlined
        dense
        v-model="input.value"
        :label="getInputLabel(input)"
        :required="input.required"
        :rules="input.required ? [requiredRule] : []"
        :error-messages="errorMessage(input)"
      ></v-textarea>
    </template>
    <template v-else-if="input.type === 'file'">
      <v-file-input
        outlined
        dense
        counter
        show-size
        :label="getInputLabel(input)"
        @change="(file) => handleFileUpload(file, input)"
        :required="input.required"
        :rules="input.required ? [requiredRule] : []"
        :error-messages="errorMessage(input)"
      >
      </v-file-input>
    </template>
    <template v-else-if="input.type === 'select'">
      <v-select
        v-model="input.value"
        :items="input.childList"
        item-text="text"
        :label="getInputLabel(input)"
        :required="input.required"
        :rules="input.required ? [requiredRule] : []"
        :error-messages="errorMessage(input)"
        outlined
        dense
      ></v-select>
    </template>
    <template v-else-if="input.type === 'radio'">
      <v-radio-group
        v-model="input.selectedOption"
        :label="getInputLabel(input)"
        :required="input.required"
        :rules="input.required ? [requiredRule] : []"
        :error-messages="errorMessage(input)"
      >
        <v-radio
          v-for="(option, optionIndex) in input.childList"
          :key="optionIndex"
          :label="option.text"
          :value="option.text"
        ></v-radio>
      </v-radio-group>
    </template>
  </template>
</template>

<script>
export default {
  props: {
    input: {
      type: Object,
    },
  },
};
</script>

<style lang="scss" scoped></style>
