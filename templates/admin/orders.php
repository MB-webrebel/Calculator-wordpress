<div class="ccb-settings-wrapper calculator-orders">
    <div class="ccb-settings-container">
            <div class="ccb-settings-content">
                <div class="ccb-settings-section">
                    <div class="existing-header">
                        <div class="existing-header__title">
                            <h4><?php esc_html_e('Orders', 'cost-calculator-builder')?></h4>
                        </div>
                    </div>
                    <div class="orders-body">
                        <template>
                            <div class="orders-wrapper" >
                                <div class="order-settings">
                                    <div>
                                        <div class="payment-actions">
                                            <div class="bulk-actions">
                                                <select v-model="sort.payment" class="order-select" @change="resetPage">
                                                    <option value="all"><?php esc_html_e('All payments', 'cost-calculator-builder')?></option>
                                                    <option value="no_payments" class="hide-if-no-js"><?php esc_html_e('No payments', 'cost-calculator-builder')?></option>
                                                    <option value="stripe" class="hide-if-no-js"><?php esc_html_e('Stripe', 'cost-calculator-builder')?></option>
                                                    <option value="paypal"><?php esc_html_e('Paypal', 'cost-calculator-builder')?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="payment-actions">
                                            <div class="bulk-actions">
                                                <select v-model="sort.status" class="order-select" @change="resetPage">
                                                    <option value="all"><?php esc_html_e('Any status', 'cost-calculator-builder')?></option>
                                                    <option value="pending" class="hide-if-no-js"><?php esc_html_e('Pending', 'cost-calculator-builder')?></option>
                                                    <option value="complete" class="hide-if-no-js"><?php esc_html_e('Complete', 'cost-calculator-builder')?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="payment-actions">
                                            <div class="bulk-actions">
                                                <select v-model="sort.calc_id" class="order-select" @change="resetPage">
                                                    <option value="all"><?php esc_html_e('All Calculators', 'cost-calculator-builder')?></option>
                                                    <option :value="calc.calc_id" class="hide-if-no-js" v-for="calc in this.calculatorList">{{ calc.calc_title }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="orders-list header">
                                    <div class="list-title check"><input type="checkbox" v-model="selectAll" @change="checkAll"></div>
                                        <div
                                            class="list-title sortable <?php echo esc_attr('id') ?>"
                                            :class="isActiveSort('<?php echo esc_attr('id') ?>')"
                                            @click="setSort('<?php echo esc_attr('id'); ?>')"
                                        >
                                            <span><?php esc_html_e('ID', 'cost-calculator-builder'); ?></span>
                                        </div>

                                        <div
                                            class="list-title <?php echo esc_attr('email') ?>"
                                        >
                                            <span><?php esc_html_e('Email', 'cost-calculator-builder'); ?></span>
                                        </div>
                                        <div
                                            class="list-title <?php echo esc_attr('title') ?>"
                                        >
                                            <span><?php esc_html_e('Calculator name', 'cost-calculator-builder'); ?></span>
                                        </div>
                                        <div
                                            class="list-title <?php echo esc_attr('payment') ?>"
                                        >
                                            <span><?php esc_html_e('Payment method', 'cost-calculator-builder'); ?></span>
                                        </div>
                                        <div
                                            class="list-title sortable <?php echo esc_attr('total') ?>"
                                            :class="isActiveSort('<?php echo esc_attr('total') ?>')"
                                            @click="setSort('<?php echo esc_attr('total'); ?>')"
                                        >
                                            <span><?php esc_html_e('Total', 'cost-calculator-builder'); ?></span>
                                        </div>
                                        <div
                                            class="list-title sortable <?php echo esc_attr('status') ?>"
                                            :class="isActiveSort('<?php echo esc_attr('status') ?>')"
                                            @click="setSort('<?php echo esc_attr('status'); ?>')"
                                        >
                                            <span><?php esc_html_e('Status', 'cost-calculator-builder'); ?></span>
                                        </div>
                                        <div
                                            class="list-title sortable <?php echo esc_attr('created_at') ?>"
                                            :class="isActiveSort('<?php echo esc_attr('created_at') ?>')"
                                            @click="setSort('<?php echo esc_attr('created_at'); ?>')"
                                        >
                                            <span><?php esc_html_e('Date', 'cost-calculator-builder'); ?></span>
                                        </div>
                                        <div class="list-title <?php echo esc_attr('details') ?>">
                                            <span><?php esc_html_e('Details', 'cost-calculator-builder'); ?></span>
                                        </div>
                                    </div>
                                <loader v-if="!ordersList" style="margin-top: 380px"></loader>
                                <div v-else-if="ordersList.length === 0">
                                    <div class="missing-orders">
                                        <div class="missing-orders__icon"><i class="fas fa-file-invoice-dollar"></i></div>
                                        <h4 class="missing-orders__title"><?php esc_html_e("No orders yet", 'cost-calculator-builder') ?></h4>
                                    </div>
                                </div>
                                <template v-else>
                                    <orders-item
                                        v-for="order in ordersList"
                                        :key="order.id"
                                        :order="order"
                                        :selected="order.selected"
                                        @order-selected="onSelected"
                                    ></orders-item>
                                    <div class="orders-controllers">
                                        <div class="order-bulk-actions">
                                            <div class="bulk-actions">
                                                <select v-model="bulkAction" class="order-select">
                                                    <option value="none"><?php esc_html_e('Bulk actions', 'cost-calculator-builder')?></option>
                                                    <option value="complete" class="hide-if-no-js"><?php esc_html_e('Complete', 'cost-calculator-builder')?></option>
                                                    <option value="pending" class="hide-if-no-js"><?php esc_html_e('Pending', 'cost-calculator-builder')?></option>
                                                    <option value="delete"><?php esc_html_e('Delete', 'cost-calculator-builder')?></option>
                                                </select>
                                                <button type="button" class="green" @click="updateMany"><?php esc_html_e('Apply', 'cost-calculator-builder')?></button>
                                            </div>
                                        </div>
                                        <div class="orders-pagination">
                                            <button @click="prevPage" v-if="sort.page != 1"> <span class="fas fa-chevron-left"></span> </button>
                                            <button v-for="n in totalPages" :key="n" :class="{active: n === sort.page}" @click="getPage(n)" :disabled="n == sort.page">{{ n }}</button>
                                            <button @click="nextPage" v-if="sort.page != totalPages"> <span class="fas fa-chevron-right"></span></button>
                                        </div>
                                        <div class="order-page-actions">
                                            <div class="bulk-actions">
                                                <select v-model="sort.limit" @change="resetPage" class="order-select">
                                                    <option value="5"><?php esc_html_e('5 orders per page', 'cost-calculator-builder')?></option>
                                                    <option value="10" class="hide-if-no-js"><?php esc_html_e('10 orders per page', 'cost-calculator-builder')?></option>
                                                    <option value="15" class="hide-if-no-js"><?php esc_html_e('15 orders per page', 'cost-calculator-builder')?></option>
                                                    <option value="20"><?php esc_html_e('20 orders per page', 'cost-calculator-builder')?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <modal ref="order-modal"></modal>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
</div>