<?php

namespace Drupal\sphproducts\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Provides a 'ShowqrBlock' block.
 *
 * @Block(
 *  id = "showqr_block",
 *  admin_label = @Translation("Show QR block"),
 * )
 */
class ShowqrBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof \Drupal\node\NodeInterface) {
      // You can get nid and anything else you need from the node object.
      $nid = $node->id();
    }
    $path = getcwd()."/sites/default/files/";
    $filename = "qrcode-".$nid.".png";
    $renderer = new ImageRenderer(
        new RendererStyle(400),
        new ImagickImageBackEnd()
    );
    $writer = new Writer($renderer);
    $writer->writeFile('Hello World!'.$nid, $path.$filename);

    return array(
      '#type' => 'markup',
      '#cache' => [
        'max-age' => 0,
      ],
      '#markup' => '<img src="/sites/default/files/'.$filename.'">',
		);
  }
  /**
   * {@inheritdoc}
   */
  /* Exclude this block from Caching */
  public function getCacheMaxAge() {
    return 0;
 }
}
