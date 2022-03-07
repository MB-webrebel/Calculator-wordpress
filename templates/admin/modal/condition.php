<div class="modal-header condition" v-if="$store.getters.getConditionData">
    <div class="modal-header__title">
        <div class="modal-title">
            <div class="add-condition-link-header">
                <span class="title">
                    <?php esc_html_e('Edit Link', 'cost-calculator-builder')?>:
                </span>
                <span class="link-fields">
                    {{ getByAlias($store.getters.getConditionData.optionFrom).label || 'Element From' }}
                    <i class="field-arrow"></i>
                    {{ getByAlias($store.getters.getConditionData.optionTo).label || 'Element From' }}
                </span>
            </div>
        </div>
    </div>
</div>
<div class="modal-body condition">
    <div class="condition-item" v-for="(model, index) in $store.getters.getConditionModel" v-if="$store.getters.getConditionModel.length > 0">
        <div class="conditions">
            <div class="condition-list" v-for="( additionalCondition, additionalConditionIndex ) in model.conditions" >
                <div class="condition">
                    <div class="select-with-label">
                        <div class="select-label">
                            <?php esc_html_e('Condition', 'cost-calculator-builder')?>
                        </div>
                        <select @change="checkCorrectForAdditionalAnd(index)" v-model="additionalCondition.condition">
                            <option value=""><?php esc_html_e('Select Condition', 'cost-calculator-builder')?></option>
                            <option v-for="(conditionState, key) in  $store.getters.getStaticConditionStatesByField( $store.getters.getConditionData.optionFrom )"
                                    :value="conditionState.value">
                                {{ conditionState.title }}
                            </option>
                        </select>
                    </div>
                    <div class="select-with-label">
                        <div class="select-label">
                            <?php esc_html_e('Value', 'cost-calculator-builder')?>
                        </div>
                        <select v-model="additionalCondition.key" v-if="$store.getters.getConditionData.type === 'select'">
                            <option value=""><?php esc_html_e('Select Option', 'cost-calculator-builder')?></option>
                            <option v-for="(item, key) in $store.getters.getConditionOptions" :value="key">
                                {{ item.optionText }}
                            </option>
                        </select>
                        <input v-else type="number" v-model="additionalCondition.value" placeholder="<?php esc_html_e('Set value', 'cost-calculator-builder')?>" />
                    </div>
                    <i class="remove-condtion" @click.prevent="removeCondition(index, additionalConditionIndex)" ></i>
                </div>
                <div class="add-condition-border">
                    <i v-if="(additionalConditionIndex + 1) == model.conditions.length" class="add-condition" @click.prevent="addRowForOrAndCondition(index)"></i>
                    <select v-else v-model="additionalCondition.logicalOperator" class="additional-condition-operator">
                        <option v-if="checkIsCanAddMultipleConditionInRow($store.getters.getConditionData.optionFrom, additionalCondition.condition)" value="&&"><?php esc_html_e('And', 'cost-calculator-builder')?></option>
                        <option value="||"><?php esc_html_e('Or', 'cost-calculator-builder')?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="action">
            <div class="select-with-label">
                <div class="select-label">
                    <?php esc_html_e('Action', 'cost-calculator-builder')?>
                </div>
                <select name="conditionAction" @change="cleanSetVal(index)" v-model="model.action">
                    <option value=""><?php esc_html_e('Select Action', 'cost-calculator-builder')?></option>
                    <option v-for="conditionActions in $store.getters.getStaticConditionActionsByField($store.getters.getConditionData.optionTo)" :value="conditionActions.value">
                        {{ conditionActions.title }}
                    </option>
                </select>
            </div>
            <!--        SET VALUE START-->
            <div class="select-with-label" v-if="model.action === 'set_value' || model.action === 'set_value_and_disable'">
                <div class="select-label">
                    <?php esc_html_e('Value', 'cost-calculator-builder')?>
                </div>
                <input type="number" v-model="model.setVal">
            </div>
            <!--        SET VALUE END-->

            <!--        SET DATE START-->
            <div class="select-with-label" v-if="model.action === 'set_date' || model.action === 'set_date_and_disable'">
                <div class="select-label">
                    <?php esc_html_e('Date', 'cost-calculator-builder')?>
                </div>
                <div class="custom-input-date">
                    <span v-if="model.setVal.length > 0" class="ccb-date-value" >
                        {{ model.setVal }}
                        <i class="far fa-times-circle fa-lg" @click="cleanSetVal(index)"></i>
                    </span>
                    <span v-else class="ccb-date-value">
                        <?php esc_html_e('Select Date', 'cost-calculator-builder')?>
                    </span>
                    <span v-if="model.setVal.length <= 0" class="ccb-datepicker-toggle">
                      <span class="datepicker-toggle-button"></span>
                      <input type="date" class="datepicker-input" @change="setDate(event, index)">
                    </span>
                </div>
            </div>
            <!--        SET DATE END-->

            <!--        SET PERIOD FOR DATE WITH RANGE START-->
            <!--        start date-->
            <div class="select-with-label" v-if="$store.getters.getFieldNameByFieldId($store.getters.getConditionData.optionTo) == 'datePicker' && ( model.action === 'set_period' || model.action === 'set_period_and_disable') ">
                <div class="select-label">
                    <?php esc_html_e('From', 'cost-calculator-builder')?>
                </div>
                <div class="custom-input-date">
                    <span v-if="( model.setVal.length > 0 && JSON.parse(model.setVal).hasOwnProperty('start') && JSON.parse(model.setVal)['start'].length > 0)" class="ccb-date-value range" >
                        {{ JSON.parse(model.setVal)['start'] }}
                        <i class="far fa-times-circle fa-lg" @click="cleanDateRangeSetVal(index, 'start')"></i>
                    </span>
                    <span class="ccb-datepicker-toggle" v-if="( model.setVal.length <= 0 || !JSON.parse(model.setVal).hasOwnProperty('start') || JSON.parse(model.setVal)['start'].length <= 0)">
              <span class="datepicker-toggle-button"></span>
              <input type="date" name="start" class="datepicker-input" @change="setRangeDate(event, index)">
            </span>
                </div>
                <span class="error-tip" v-if="errors.range_date_error != null" v-html="errors.range_date_error"></span>
            </div>
            <!--        end date-->
            <div class="select-with-label" v-if="$store.getters.getFieldNameByFieldId($store.getters.getConditionData.optionTo) == 'datePicker' && ( model.action === 'set_period' || model.action === 'set_period_and_disable') ">
                <div class="select-label">
                    <?php esc_html_e('To', 'cost-calculator-builder')?>
                </div>
                <div class="custom-input-date">
                    <span v-if="( model.setVal.length > 0 && JSON.parse(model.setVal).hasOwnProperty('end') && JSON.parse(model.setVal)['end'].length > 0 )" class="ccb-date-value range" >
                        {{ JSON.parse(model.setVal)['end'] }}
                        <i class="far fa-times-circle fa-lg" @click="cleanDateRangeSetVal(index, 'end')"></i>
                    </span>
                    <span class="ccb-datepicker-toggle" v-if="( model.setVal.length <= 0 || !JSON.parse(model.setVal).hasOwnProperty('end') || JSON.parse(model.setVal)['end'].length <= 0)">
                      <span class="datepicker-toggle-button"></span>
                      <input type="date" name="end" class="datepicker-input" @change="setRangeDate(event, index)">
                    </span>
                </div>
            </div>
            <!--        SET PERIOD FOR DATE WITH RANGE END-->

            <!--        SET PERIOD FOR MULTI RANGE START-->
            <div class="select-with-label" v-if="$store.getters.getFieldNameByFieldId($store.getters.getConditionData.optionTo) == 'multi_range' && ( model.action === 'set_period' || model.action === 'set_period_and_disable') ">
                <div class="select-label">
                    <?php esc_html_e('From', 'cost-calculator-builder')?>
                </div>
                <input @change="setMultiRange(event, index)" type="number" name="start" :value="( model.setVal.length > 0 && JSON.parse(model.setVal).hasOwnProperty('start')) ? JSON.parse(model.setVal)['start']: ''">
                <span class="error-tip" v-if="errors.multi_range_error != null" v-html="errors.multi_range_error"></span>
            </div>
            <div class="select-with-label" v-if="$store.getters.getFieldNameByFieldId($store.getters.getConditionData.optionTo) == 'multi_range' && ( model.action === 'set_period' || model.action === 'set_period_and_disable') ">
                <div class="select-label">
                    <?php esc_html_e('To', 'cost-calculator-builder')?>
                </div>
                <input @change="setMultiRange(event, index)" type="number" name="end" :value="( model.setVal.length > 0 && JSON.parse(model.setVal).hasOwnProperty('end')) ? JSON.parse(model.setVal)['end']: ''">
            </div>
            <!--        SET PERIOD FOR MULTI RANGE END-->

            <!--        SET OPTION  START-->
            <div class="select-with-label" v-if="model.action === 'select_option' || model.action === 'select_option_and_disable'">
                <div class="select-label">
                    <?php esc_html_e('Option', 'cost-calculator-builder')?>
                </div>
                <!--        FOR multiple items (checkbox, toggle) -->
                <div v-if="['checkbox', 'toggle'].includes( $store.getters.getFieldNameByFieldId($store.getters.getConditionData.optionTo))" class="multiselect" tabindex="100">
                    <span v-if="model.setVal.length > 0" class="anchor" @click.prevent="multiselectShow(event)">
                        {{ model.setVal.split(',').length }} <?php esc_html_e('options selected', 'cost-calculator-builder')?>
                    </span>
                    <span v-else class="anchor" @click.prevent="multiselectShow(event)">
                        <?php esc_html_e('Select Option', 'cost-calculator-builder')?>
                    </span>
                    <ul class="items">
                        <li class="option-item" @click.self="multiselectChoose(event, optionIndex, index)"  v-for="(item, optionIndex) in $store.getters.getFieldOptionsByFieldId($store.getters.getConditionData.optionTo)" >
                            <input :checked="( model.setVal.length > 0 && model.setVal.split(',').map(Number).includes(optionIndex))" @change="multiselectChoose(event, optionIndex);" :class="['index',optionIndex].join('_')" type="checkbox"  />{{ item.optionText }}
                        </li>
                    </ul>
                    <input v-model="model.setVal" name="options" type="hidden" :class="$store.getters.getConditionData.optionTo"   />
                </div>

                <!--        FOR one value (radio, dropDown) -->
                <select v-else name="setOptions[]" v-model="model.setVal" >
                    <option v-for="(item, optionIndex) in $store.getters.getFieldOptionsByFieldId($store.getters.getConditionData.optionTo)" :value="optionIndex">
                        {{ item.optionText }}
                    </option>
                </select>
            </div>
            <!--        SET OPTION END-->
        </div>
        <div class="remove-full-condition" @click.prevent="removeRow(index)">
            <i class="remove-icon"></i>
        </div>
    </div>


    <div v-if="!$store.getters.getConditionModel.length" class="modal-body">
        <p style="color: #333333; width: 100%; text-align: center; padding: 5px; opacity: .7">
            <?php esc_html_e('No Conditions Yet', 'cost-calculator-builder')?>
        </p>
    </div>
</div>
<div class="modal-footer">
    <div class="condition">
            <div class="left">
                <button  @click.prevent="addModel"  type="button" class="modal-btn green">
                    <i class="fas fa-plus"></i>
                    <span><?php esc_html_e('Add condition', 'cost-calculator-builder')?></span>
                </button>
            </div>

            <div class="right">
                <button type="button" class="modal-btn white" @click.prevent="removeLink()">
                    <span><?php esc_html_e('Delete', 'cost-calculator-builder')?></span>
                </button>
                <button type="button" class="modal-btn green" @click.prevent="saveLink">
                    <span><?php esc_html_e('Save', 'cost-calculator-builder')?></span>
                </button>
            </div>
    </div>
</div>