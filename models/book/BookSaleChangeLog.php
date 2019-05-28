<?php

namespace app\models\book;

use Yii;

/**
 * This is the model class for table "book_sale_change_log".
 *
 * @property string $id
 * @property int $book_id 商品id
 * @property int $quantity 售卖数量
 * @property string $price 售卖金额
 * @property int $member_id 会员id
 * @property string $created_time 售卖时间
 */
class BookSaleChangeLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book_sale_change_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id', 'quantity', 'member_id'], 'integer'],
            [['price'], 'number'],
            [['created_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'quantity' => 'Quantity',
            'price' => 'Price',
            'member_id' => 'Member ID',
            'created_time' => 'Created Time',
        ];
    }
}
