<?php
class FireGento_AdminMonitoring_Model_Observer_Log
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function log(Varien_Event_Observer $observer)
    {
        /**
         * @var $history FireGento_AdminMonitoring_Model_History
         */
        $history = Mage::getModel('firegento_adminmonitoring/history');
        $history->setData(
            array(
                 'object_id'    => $observer->getObjectId(),
                 'object_type'  => $observer->getObjectType(),
                 'content'      => $observer->getContent(),
                 'content_diff' => $observer->getContentDiff(),
                 'user_agent'   => $this->getUserAgent(),
                 'ip'           => $this->getRemoteAddr(),
                 'user_id'      => $this->getUserId(),
                 'user_name'    => $this->getUserName(),
                 'action'       => $observer->getAction(),
                 'created_at'   => now(),
            )
        );
        $history->save();
    }

    /**
     * @return int
     */
    protected function getUserId()
    {
        if ($this->getUser()) {
            $userId = $this->getUser()
                ->getUserId();
        } else {
            $userId = 0;
        }
        return $userId;
    }

    /**
     * @return string
     */
    protected function getUserName()
    {
        if ($this->getUser()) {
            $userName = $this->getUser()
                ->getUsername();
        } else {
            $userName = '';
        }
        return $userName;
    }

    /**
     * @return Mage_Admin_Model_User|NULL
     */
    protected function getUser()
    {
        /**
         * @var $session Mage_Admin_Model_Session
         */
        $session = Mage::getSingleton('admin/session');
        return $session->getUser();
    }

    /**
     * @return string
     */
    protected function getUserAgent()
    {
        return (isset($_SERVER['HTTP_USER_AGENT']) ? (string) $_SERVER['HTTP_USER_AGENT'] : '');
    }

    /**
     * @return MageTest_Core_Helper_Http
     */
    protected function getRemoteAddr()
    {
        return Mage::helper('core/http')
            ->getRemoteAddr();
    }

}