<?php
/**
 * Date: 03/03/14
 * Time: 14:05
 */

namespace Muzar\BazaarBundle\FormType;

use Muzar\BazaarBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemFormType extends AbstractType
{

	/**
	 * @var Category[]
	 */
	protected $categories;

	function __construct(array $categories)
	{
		$this->categories = $categories;
	}


	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder
			->add('name', 'text')
			->add('description', 'textarea')
			->add('price', 'integer')
			->add('negotiablePrice', 'checkbox')
			->add('allowSendingByMail', 'checkbox');

		$builder->add('category', 'entity', array(
			'class' => 'Muzar\BazaarBundle\Entity\Category',
			'choices' => $this->categories,
			'property' => 'path',
		));

	}


	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Muzar\BazaarBundle\Entity\Item',
			'csrf_protection'   => FALSE,
		));
	}

	public function getName()
	{
		return 'item';
	}

} 