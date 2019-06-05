<?php

namespace app\models\stat;

use Yii;

/**
 * This is the model class for table "stat_daily_member".
 *
 * @property string $id
 * @property string $date 日期
 * @property int $member_id 会员id
 * @property int $total_shared_count 当日分享总次数
 * @property string $total_pay_money 当日付款总金额
 * @property string $updated_time 最后一次更新时间
 * @property string $created_time 插入时间
 */
class StatDailyBook extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stat_daily_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'required'],
            [['date', 'updated_time', 'created_time'], 'safe'],
            [['member_id', 'total_shared_count'], 'integer'],
            [['total_pay_money'], 'number'],
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
            'member_id' => 'Member ID',
            'total_shared_count' => 'Total Shared Count',
            'total_pay_money' => 'Total Pay Money',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
