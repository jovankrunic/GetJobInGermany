// src/SkyResource/GetJobInGermanyBundle/Services/HalloService.php
namespace SkyResource\GetJobInGermanyBundle\Services;

class HalloService
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function hello($name)
    {

        $message = \Swift_Message::newInstance()
                                ->setTo('jovan.krunic@gmail.com')
                                ->setSubject('Hello Service')
                                ->setBody($name . ' says hi!');

        $this->mailer->send($message);

        return 'Hello, '.$name;
    }
}