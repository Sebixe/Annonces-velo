<?php


namespace App\Service\Menu;


use App\Repository\CategoryRepository;
use App\Service\Menu\MenuElementModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Security;

class Menu
{

    private CategoryRepository $categoryRepository;
    private UrlGeneratorInterface $urlGenerator;
    private Security $security;

    /**
     * Menu constructor.
     * @param CategoryRepository $categoryRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @param Security $tokenStorage
     */
    public function __construct(CategoryRepository $categoryRepository, UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->categoryRepository = $categoryRepository;
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }


    public function getMenu(): array {
        $menu = [];

        $mainCategories = new MenuElementModel();
        $mainCategories->setTitle('Catégories');

        $categories = $this->categoryRepository->findAll();
        $menu[] = $mainCategories;
        foreach ($categories as $category) {
            $menuCategory = new MenuElementModel();
            $menuCategory->setTitle($category->getName());
            $menuCategory->setLink($this->urlGenerator->generate('showByCategory', ['name' => $category->getName()]));
            $mainCategories->addChild($menuCategory);
        }

        $user = $this->security->getUser();
        if ($user === null){
            $menuLogin = new MenuElementModel();
            $menuLogin->setTitle("Se connecter");
            $menuLogin->setLink($this->urlGenerator->generate('app_login'));
            $menu[] = $menuLogin;

            $menuRegister = new MenuElementModel();
            $menuRegister->setTitle("Créer un compte");
            $menuRegister->setLink($this->urlGenerator->generate('register'));
            $menu[] = $menuRegister;
        } else {
            $menuUser = new MenuElementModel();
            $menuUser->setTitle("Mon Compte");

            $menuMyAdverts = new MenuElementModel();
            $menuMyAdverts->setTitle('Mes annonces');
            $menuMyAdverts->setLink($this->urlGenerator->generate('accountMyAdvert'));
            $menuUser->addChild($menuMyAdverts);


            $menuLogout = new MenuElementModel();
            $menuLogout->setTitle('Se déconnecter');
            $menuLogout->setLink($this->urlGenerator->generate('app_logout'));

            $menuUser->addChild($menuLogout);

            $menu[] = $menuUser;
        }
        dump($menu);
        return $menu;
    }

}
