<?php

namespace app\models\stat;

use Yii;

/**
 * This is the model class for table "stat_daily_site".
 *
 * @property string $id
 * @property string $date 日期
 * @property string $total_pay_money 当日应收总金额
 * @property int $total_member_count 会员总数
 * @property int $total_new_member_count 当日新增会员数
 * @property int $total_order_count 当日订单数
 * @property int $total_shared_count
 * @property string $updated_time 最后一次更新时间
 * @property string $created_time 插入时间
 */
class StatDailySite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stat_daily_site';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'total_member_count', 'total_new_member_count', 'total_order_count', 'total_shared_count'], 'required'],
            [['date', 'updated_time', 'created_time'], 'safe'],
            [['total_pay_money'], 'number'],
            [['total_member_count', 'total_new_member_count', 'total_order_count', 'total_shared_count'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'total_pay_money' => 'Total Pay Money',
            'total_member_count' => 'Total Member Count',
            'total_new_member_count' => 'Total New Member Count',
            'total_order_count' => 'Total Order Count',
            'total_shared_count' => 'Total Shared Count',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
