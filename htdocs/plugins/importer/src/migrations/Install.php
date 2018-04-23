<?php
    /**
    * Created by PhpStorm.
    * User: admin
    * Date: 26/3/18
    * Time: 1:00 PM
    */
    //TODO
    /*
    * we need to optimize database creation and deletion process.
    */
    namespace fatfish\importer\migrations;
    use Craft;
    use craft\db\Migration;

    class Install extends Migration
    {

    public $driver;

    public function safeUp()
    {

        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->createTables();
        $this->createFeedTables();
        $this->createFeedMapping();
        return true;
    }


        public function safeDown()
        {
            $this->driver = Craft::$app->getConfig()->getDb()->driver;
            $this->removeFeedtable();
            $this->removeTables();
            $this->removeFeedMappingtable();

        }


        public function createTables()
            {
                $this->createTable(
                '{{%importer_field_mapping}}',
                [
                    'id' => $this->primaryKey(),
                    'entries_field' => $this->integer(),
                    'mapped_field' => $this->string(45),
                    'critearea' => $this->integer(),
                    'uid' => $this->uid(),
                    'dateCreated' => $this->dateTime(),
                    'dateUpdated' => $this->dateTime(),
                ]);
            }

        public function createFeedTables()
        {
            $this->driver = Craft::$app->getConfig()->getDb()->driver;
            $this->createTable(
              '{{%importer_feeds}}',
              [
               'id'=>$this->primaryKey(),
               'name'=>$this->string(),
               'feedurl'=>$this->string(),
               'feedtype'=>$this->string(),
               'entry_type'=>$this->integer(),
               'uid'=>$this->uid(),
               'dateCreated'=>$this->dateTime(),
               'dateUpdated'=>$this->dateTime(),

              ]);

        }

        public function createFeedMapping()
        {
            $this->driver = Craft::$app->getConfig()->getDb()->driver;

            $this->createTable('{{%importer_feed_mapping}}',[

                    'id' => $this->primaryKey(),
                    'entries_field' => $this->integer(),
                    'mapped_field' => $this->string(45),
                    'critearea' => $this->integer(),
                    'feed_type' => $this->string(),
                    'uid' => $this->uid(),
                    'dateCreated' => $this->dateTime(),
                    'dateUpdated' => $this->dateTime(),
            ]);
        }


    public function removeTables()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
           $this->dropTable('{{%importer_field_mapping}}');
    }

    public function removeFeedtable()
    {


        $this->dropTable('{{%importer_feeds}}');
    }

        public function removeFeedMappingtable()
        {
            $this->driver = Craft::$app->getConfig()->getDb()->driver;
            $this->dropTable('{{%importer_feed_mapping}}');


        }
    }