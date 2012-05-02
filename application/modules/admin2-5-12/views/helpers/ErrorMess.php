<?php
class Application_Helpers_Default_ErrorMess extends Zend_Controller_Action_Helper_Abstract
{
	public function __construct()
	{
		
	}
	
	public function ErrorMess()
	{
		
		return 'You do not have rights to access this module';
	}
        public function errMess()
	{

		return 'Participation is required';
	}
	
	public function UserAddMess()
	{
		
		return '<br />Danke für\'s Mitmachen!<br />Viel Glück und bis bald!<br />Ihr SCHNEEKOPPE-Team.<br />';
	}
	public function UserEditMess()
	{
		
		return 'Benutzer wurde erfolgreich bearbeitet';
	}
	
	public function ZipcodeAddMess()
	{
		
		return 'Zip Code has been added successfully';
	}
	
	public function ZipcodeEditMess()
	{
		
		return 'Zip Code has been edited successfully';
	}
	public function NeswAddMess()
	{
		
		return 'News has been added successfully';
	}
	public function CountryAddMess()
	{
		
		return 'Country has been added successfully';
	}
		public function CountryEditMess()
	{
		
		return 'Country has been edited successfully';
	}
		public function CityAddMess()
	{
		
		return 'City has been added successfully';
	}
	public function CityEditMess()
	{
		
		return 'City has been edited successfully';
	}
	public function LanguageAddMess()
	{
		
		return 'Language has been added successfully';
	}
	public function LanguageEditMess()
	{
		
		return 'Language has been edited successfully';
	}
		public function CategoryAddMess()
	{
		
		return 'Location Category has been added successfully';
	}
		public function CategoryEditMess()
	{
		
		return 'Location Category has been edited successfully';
	}
		public function LocationAddMess()
	{
		
		return 'Location has been added successfully';
	}
		public function LocationEditMess()
	{
		
		return 'Location has been edited successfully';
	}
		public function GroupMoudleAddMess()
	{
		
		return 'Group has been added successfully';
	}
		public function GroupMoudleEditMess()
	{
		
		return 'Group has been edited successfully';
	}
		public function CustomerEditMess()
	{
		
		return 'Customer info updated successfully';
	}
	public function CustomerPasswordMess()
	{
		return 'Password Reset Successfuly';
	}
	public function EventAddMess()
	{
		return 'Event has been added successfully';
	}
	public function EventEditMess()
	{
		return 'Event has been edited successfully';
	}
	public function  EventAddLocationMess()
	{
		return 'Event Location has been addeded successfully';
	}
	public function EventSendMessMess()
	{
	return 'Your Message has been Sent Successfully!';
	}
	public function  EventEditedLocationMess()
	{
		return 'Event Location has been edited successfully';
	}
	public function  EventMessSendMess()
	{
	return 'Your Messages has been Sent Successfully';
	}
	public function  EventAddParticipantsMess()
	{
	return 'Participant has been added successfully';
	}
	public function  PageAddMess()
	{
	return 'Page has been added successfully';
	}
	public function AdminmailComposeMess()
	{
		return 'Your message has been sent successfully';
	}
	public function EmailTemplateAddMess()
	{
		return 'Email Template has been added successfully';
	}
	public function EmailTemplateEditMess()
	{
		return 'Email Template has been edited successfully';
	}
	public function MessageTemplateAddMess()
	{
		return 'Message Template has been added successfully';
	}
	public function EventtypeAddMess()
	{
		return 'Event Type has been added successfully';
	}
	public function EventTemplateAddMess()
	{
		return 'Event Template has been added successfully';
	}
	public function EventTemplateEditMess()
	{
		return 'Event Template has been edited successfully';
	}
	public function PasswordChangeMess()
	{
	return 'Password Changed Successfully';
	}
	public function EventParticipantMess()
	{
	return 'Participant doesn\'t exist';
	}
	public function PlaneAddMess()
	{
		return 'Plan has been added successfully';
	}
	public function PlanEditMess()
	{
		return 'Plan has been edited successfully';
	}
	public function inboxrepMessMess()
	{
		return 'Your message has been sent successfully';
	}
	public function EventEditParticipantsMess()
	{
		return 'Participant has been edited successfully';
	}
	public function AddCampaignMess()
	{
		return 'Campaign has been added successfully';
	}
	public function EditCampaignMess()
	{
		return 'Campaign has been edited successfully';
	}
	public function DeleteCampaignMess()
	{
		return 'Campaign has been deleted successfully';
	}
	public function AddCouponMess()
	{
		return 'Coupons has been added successfully';
	}
	public function EditCouponMess()
	{
		return 'Coupon has been edited successfully';
	}
	public function DeleteCouponMess()
	{
		return 'Coupon has been deleted successfully';
	}
	public function AddCustomerLanguageMess()
	{
		return 'Customer Language has been added successfully';
	}
	public function EditCustomerLanguageMess()
	{
		return 'Customer Language has been edited successfully';
	}
	public function DeleteCustomerLanguageMess()
	{
		return 'Customer Language has been deleted successfully';
	}
	public function AddQualificationMess()
	{
		return 'Language Qualification has been added successfully';
	}
	public function EditQualificationMess()
	{
		return 'Language Qualification  has been edited successfully';
	}
	public function DeleteQualificationMess()
	{
		return 'Language Qualification  has been deleted successfully';
	}
	public function AddRelationMess()
	{
		return 'Relationship has been added successfully';
	}
	public function EditRelationMess()
	{
		return 'Relationship has been edited successfully';
	}
	public function DeleteRelationMess()
	{
		return 'Relationship has been deleted successfully';
	}
	public function EditBlogCatMess()
	{
		return 'Blog Category has been edited successfully';
	}
	public function AddPostMess()
	{
		return 'Blog Post has been added successfully';
	}
	public function EditPostMess()
	{
		return 'Blog Post has been edited successfully';
	}
	public function DeletePostMess()
	{
		return 'Blog Post has been deleted successfully';
	}
	public function AddSkillMess()
	{
		return 'Skill has been added successfully';
	}
	public function EditSkillMess()	
	{
		return 'Skill has been edited successfully';
	}
	public function DeleteSkillMess()	
	{
		return 'Skill has been deleted successfully';
	}
	public function AddWhenMess()
	{
		return 'When occurence has been added successfully';
	}
	public function EditWhenMess()	
	{
		return 'When occurence has been edited successfully';
	}
	public function DeleteWhenMess()	
	{
		return 'When occurence has been deleted successfully';
	}
	public function AddFreqMess()
	{
		return 'Interest frequency has been added successfully';
	}
	public function EditFreqMess()	
	{
		return 'Interest frequency has been edited successfully';
	}
	public function DeleteFreqMess()	
	{
		return 'Interest frequency has been deleted successfully';
	}
	public function AddTestMess()
	{
		return 'Testimonial has been added successfully';
	}
	public function EditTestMess()	
	{
		return 'Testimonial has been edited successfully';
	}
	public function DeleteTestMess()	
	{
		return 'Testimonial has been deleted successfully';
	}
	public function AddDiseaseMess()
	{
		return 'Disaese wurde erfolgreich hinzugefügt';
	}
	public function EditDiseaseMess()	
	{
		return 'Disease wurde erfolgreich bearbeitet';
	}
        public function EditSubmissionMess()
	{
		return 'Ticket muss der Benutzer zugeordnet';
	}
        public function DeleteSubmissionMess()
	{
		return 'Benutzer wurde gelöscht';
	}
	public function DeleteDiseaseMess()	
	{
		return 'Disease wurde erfolgreich gelöscht';
	}
		public function AddTipMess()
	{
		return 'Tipp wurde erfolgreich hinzugefügt';
	}
	public function EditTipMess()	
	{
		return 'Tipp wurde erfolgreich bearbeitet';
	}
	public function DeleteTipMess()	
	{
		return 'Tipp wurde erfolgreich gelöscht';
	}
	public function EditSubinterestMess()	
	{
		return 'Subinterest has been edited successfully';
	}
	public function DeleteSubinterestMess()	
	{
		return 'Subinterest has been deleted successfully';
	}
	public function AddCultureMess()
	{
		return 'Culture has been added successfully';
	}
	public function EditCultureMess()	
	{
		return 'Culture has been edited successfully';
	}
	public function DeleteCultureMess()	
	{
		return 'Culture has been deleted successfully';
	}
	public function AddSubCultureMess()
	{
		return 'Subculture has been added successfully';
	}
	public function EditSubCultureMess()	
	{
		return 'Subculture has been edited successfully';
	}
	public function DeleteSubCultureMess()	
	{
		return 'Subculture has been deleted successfully';
	}
	public function AddSubinterestMess()
	{
		return 'Subinterest has been added successfully';
	}
	public function AddHobbyMess()
	{
		return 'Hobby has been added successfully';
	}
	public function EditHobbyMess()	
	{
		return 'Hobby has been edited successfully';
	}
	public function DeleteHobbyMess()	
	{
		return 'Hobby has been deleted successfully';
	}
	public function AddSubhobbyMess()
	{
		return 'Subhobby has been added successfully';
	}
	public function EditSubhobbyMess()	
	{
		return 'Subhobby has been edited successfully';
	}
	public function DeleteSubhobbyMess()	
	{
		return 'Subhobby has been deleted successfully';
	}
		public function AddPetMess()
	{
		return 'Pet has been added successfully';
	}
	public function EditPetMess()	
	{
		return 'Pet has been edited successfully';
	}
	public function DeletePetMess()	
	{
		return 'Pet has been deleted successfully';
	}
	public function AddSubpetMess()
	{
		return 'Subpet has been added successfully';
	}
	public function EditSubpetMess()	
	{
		return 'Subpet has been edited successfully';
	}
	public function DeleteSubpetMess()	
	{
		return 'Subpet has been deleted successfully';
	}
	
	public function AddSportMess()
	{
		return 'Sport has been added successfully';
	}
	public function EditSportMess()	
	{
		return 'Sport has been edited successfully';
	}
	public function DeleteSportMess()	
	{
		return 'Sport has been deleted successfully';
	}
	public function AddSubSportMess()
	{
		return 'Sub Sport has been added successfully';
	}
	public function EditSubSportMess()	
	{
		return 'Sub Sport has been edited successfully';
	}
	public function DeleteSubSportMess()	
	{
		return 'Sub Sport has been deleted successfully';
	}
	public function AddtravelMess()
	{
		return 'Travel has been added successfully';
	} 
	public function EdittravelMess()
	{
		return 'Travel has been edited successfully';
	}
	public function DeletetravelMess()
	{
		return 'Travel has been deleted successfully';
	}  
	public function AdminmailDeleteMess()
	{
		return 'Message has been deleted successfully';
	}
	public function AddAdvertisementMess()
	{
		return 'Advertisement has been added successfully';
	} 
	public function EditAdvertisementMess()
	{
		return 'Advertisement has been edited successfully';
	}
	public function DeleteNewsletterMess()
	{
		return 'User has been unsubscribed successfully';
	}  

}
?>
