<?php

namespace app\models\market;

use Yii;

/**
 * This is the model class for table "market_qrcode".
 *
 * @property string $id
 * @property string $name 名字
 * @property string $qrcode 二维码内容
 * @property string $extra 接口返回的信息
 * @property string $expired_time 过期时间
 * @property int $total_scan_count 总扫码关注量
 * @property int $total_reg_count 总注册数量
 * @property string $updated_time 最后一次更新时间
 * @property string $created_time 插入时间
 */
class MarketQrcode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'market_qrcode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expired_time', 'updated_time', 'created_time'], 'safe'],
            [['total_scan_count', 'total_reg_count'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['qrcode'], 'string', 'max' => 62],
            [['extra'], 'string', 'max' => 2000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'qrcode' => 'Qrcode',
            'extra' => 'Extra',
            'expired_time' => 'Expired Time',
            'total_scan_count' => 'Total Scan Count',
            'total_reg_count' => 'Total Reg Count',
            'updated_time' => 'Updated Time',
            'created_time' => 'Created Time',
        ];
    }
}
