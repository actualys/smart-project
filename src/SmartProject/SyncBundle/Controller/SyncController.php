<?php

namespace SmartProject\SyncBundle\Controller;

use SmartProject\SyncBundle\Providers\Factory;
use SmartProject\SyncBundle\Providers\ProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class SyncController
 *
 * @package SmartProject\SyncBundle\Controller
 *
 * @Route("/synchronize")
 */
class SyncController extends Controller
{
    /**
     * @Route("/{providerCode}/run", name="sync_run")
     * @Method("GET")
     * @Template()
     */
    public function runAction($providerCode)
    {
        /** @var Factory $factory */
        $factory = $this->container->get('smart_project_sync.providers.factory');
        /** @var ProviderInterface $provider */
        $provider = $factory->getInstance($providerCode);
        $result   = $provider->execute();

        if ($result) {
            $this->get('session')->getFlashBag()->add('success', $provider->getName() . ': projects synchronized');
        }

        return $this->redirect($this->generateUrl('project'));
    }
}
