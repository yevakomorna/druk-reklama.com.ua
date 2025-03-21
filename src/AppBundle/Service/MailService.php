<?php
namespace AppBundle\Service;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;

/**
 * @Service("mailService")
 */
class MailService {

	private static $MAIL_FROM_NAME = "druk-reklama.com.ua";

	private static $MAIL_FROM_ADDR = "no-replay";
	
	private static $ADMIN_MAIL = "webmaster@druk-reklama.com.ua";

	/**
	 * @Inject
	 */
	public $router;

	/**
	 * @Inject("request_stack")
	 */
	public $request;

	/**
	 * @Inject
	 */
	public $templating;

	/**
	 * @Inject
	 */
	public $mailer;

	/**
	 * @Inject
	 */
	public $translator;
    
    /**
     * @Inject("OrderService")
     */
    public $OrderService;

	public function sendConfirmOrderMail($order) {
		$serverHost = $this->request->getCurrentRequest()->getHost();
        $user = $order->getUser();
        
        $OrderSumms = $this->OrderService->calculateOrder($order);
        
        //відправка клієнту
		$message = (new \Swift_Message())
			->setSubject($this->translator->trans("Замовлення на сайті"))
			->setFrom([self::$MAIL_FROM_ADDR."@".$serverHost => self::$MAIL_FROM_NAME])
			->setTo($user->getUsername())
			->setBody(
				$this->templating->render(
					"default/mail/confirm_order.html",
					["fullName" => $user->getFullName(),
					"serverLink" => $serverHost,
                    "sumAll" => $OrderSumms['sumAll'], 
                    "sumAditionalServise" => $OrderSumms['sumAditionalServise'],
                    "sumMaket" => $OrderSumms['sumMaket'],
                    "orderNumber"=> substr(1000000000 + $order->getID(),1),
                    ]
				),
				"text/html"
			);
		$this->mailer->send($message);
        //відправка адміну
		$message = (new \Swift_Message())
			->setSubject($this->translator->trans("Замовлення на сайті"))
			->setFrom([self::$MAIL_FROM_ADDR."@".$serverHost => self::$MAIL_FROM_NAME])
			->setTo(self::$ADMIN_MAIL)
			->setBody(
				$this->templating->render(
					"default/mail/confirm_order_admin.html",
					["fullName" => $user->getFullName(),
					"serverLink" => $serverHost,
                    "sumAll" => $OrderSumms['sumAll'], 
                    "sumAditionalServise" => $OrderSumms['sumAditionalServise'],
                    "sumMaket" => $OrderSumms['sumMaket'],
                    "orderNumber"=> substr(1000000000 + $order->getID(),1),
                    ]
				),
				"text/html"
			);
		$this->mailer->send($message);        
        
	}


}
?>